<?php

namespace App\Services;

use App\Models\PdfAccessLog;
use App\Models\PdfViewToken;
use App\Models\SecurePdf;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SecurePdfService
{
    private string $disk = 'secure_pdfs';

    /**
     * Store PDF file securely outside public folder.
     * Random filename — original name never stored on disk.
     * File path encrypted in DB.
     */
    public function store(UploadedFile $file, array $data, int $userId): SecurePdf
    {
        $storagePath = 'pdfs/' . Str::random(40) . '.pdf';

        Storage::disk($this->disk)->put(
            $storagePath,
            file_get_contents($file->getRealPath())
        );

        return SecurePdf::create([
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'file_path'     => Crypt::encryptString($storagePath),
            'original_name' => $file->getClientOriginalName(),
            'category'      => $data['category'] ?? null,
            'chapter_id'    => $data['chapter_id'] ?? null,
            'lesson_id'     => $data['lesson_id'] ?? null,
            'total_pages'   => (int)($data['total_pages'] ?? 0),
            'file_size'     => $file->getSize(),
            'is_active'     => true,
            'isPaid'        => isset($data['isPaid']) ? (bool)$data['isPaid'] : false,
            'allow_print'   => isset($data['allow_print']) ? (bool)$data['allow_print'] : false,
            'created_by'    => $userId,
        ]);
    }

    /**
     * Replace PDF file — keep same DB record.
     */
    public function replaceFile(SecurePdf $pdf, UploadedFile $file): void
    {
        $this->deleteFile($pdf);

        $storagePath = 'pdfs/' . Str::random(40) . '.pdf';
        Storage::disk($this->disk)->put(
            $storagePath,
            file_get_contents($file->getRealPath())
        );

        $pdf->update([
            'file_path'     => Crypt::encryptString($storagePath),
            'original_name' => $file->getClientOriginalName(),
            'file_size'     => $file->getSize(),
        ]);
    }

    /**
     * Generate 30-minute token — tied to user IP.
     */
    public function generateViewToken(SecurePdf $pdf, Request $request): string
    {
        // Delete old tokens for this user + pdf
        PdfViewToken::where('secure_pdf_id', $pdf->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        $token = Str::random(64);

        PdfViewToken::create([
            'secure_pdf_id' => $pdf->id,
            'user_id'       => $request->user()->id,
            'token'         => $token,
            'ip_address'    => $request->ip(),
            'expires_at'    => now()->addMinutes(30),
        ]);

        return $token;
    }

    /**
     * Validate token — IP must match, not expired, correct user.
     */
    public function validateToken(string $token, Request $request): ?PdfViewToken
    {
        return PdfViewToken::where('token', $token)
            ->where('ip_address', $request->ip())
            ->where('user_id', $request->user()->id)
            ->where('expires_at', '>', now())
            ->first();
    }

    /**
     * Decrypt and return real file path.
     */
    public function getRealPath(SecurePdf $pdf): ?string
    {
        try {
            $path = Crypt::decryptString($pdf->file_path);
            return Storage::disk($this->disk)->path($path);
        } catch (\Exception $e) {
            Log::error('SecurePdf getRealPath: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Log access event.
     */
    public function logAccess(
        int $pdfId,
        int $userId,
        Request $request,
        string $action
    ): void {
        PdfAccessLog::create([
            'secure_pdf_id' => $pdfId,
            'user_id'       => $userId,
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
            'action'        => $action,
            'accessed_at'   => now(),
        ]);
    }

    /**
     * Delete physical file from storage.
     */
    public function deleteFile(SecurePdf $pdf): void
    {
        try {
            $path = Crypt::decryptString($pdf->file_path);
            Storage::disk($this->disk)->delete($path);
        } catch (\Exception $e) {
            Log::error('SecurePdf deleteFile: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete record + delete file.
     */
    public function delete(SecurePdf $pdf): void
    {
        $this->deleteFile($pdf);
        $pdf->delete();
    }
}
