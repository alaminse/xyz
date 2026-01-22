<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LectureVideo;
use App\Models\EnrollUser;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoStreamController extends Controller
{
    /**
     * 🔐 Stream MP4 video securely (Level 2)
     */
    public function streamMp4(Request $request, $id)
    {
        $video = LectureVideo::findOrFail($id);

        // Security Check 1: Authentication
        abort_unless(Auth::check(), 403, 'Login required');

        // Security Check 2: Enrollment & Payment Status
        if ($video->isPaid) {
            $enrolled = EnrollUser::where('user_id', Auth::id())
                ->where('course_id', $video->course_id)
                ->whereIn('status', [Status::ACTIVE()->value, Status::FREETRIAL()->value])
                ->first();

            abort_unless($enrolled, 403, 'You need to enroll in this course');

            // If paid video but user is on free trial
            if ($enrolled->status == Status::FREETRIAL()->value) {
                abort(403, 'This is a paid video. Please upgrade your enrollment.');
            }
        }

        // Get video file path
        $relativePath = str_replace('storage/', '', $video->uploaded_link);

        // Try private folder first
        $path = storage_path('app/private/videos/' . basename($relativePath));

        // Fallback to public folder
        if (!file_exists($path)) {
            $path = storage_path('app/public/' . $relativePath);
        }

        abort_unless(file_exists($path), 404, 'Video file not found');

        // Stream with range support (for seeking)
        return $this->streamFileWithRange($path, $request);
    }

    /**
     * 🔥 Stream HLS files (Level 3 - Most Secure)
     */
    public function streamHls($id, $file)
    {
        $video = LectureVideo::findOrFail($id);

        // Security checks
        abort_unless(Auth::check(), 403, 'Login required');

        if ($video->isPaid) {
            $enrolled = EnrollUser::where('user_id', Auth::id())
                ->where('course_id', $video->course_id)
                ->where('status', Status::ACTIVE()->value)
                ->exists();

            abort_unless($enrolled, 403, 'Access denied');
        }

        // Validate file extension (only m3u8 and ts allowed)
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        abort_unless(
            in_array($extension, ['m3u8', 'ts']),
            403,
            'Invalid file type'
        );

        // Get HLS file path
        $path = storage_path('app/private/hls/' . $video->id . '/' . $file);

        abort_unless(file_exists($path), 404, 'HLS file not found');

        // Set proper MIME type
        $mimeType = $extension === 'm3u8'
            ? 'application/vnd.apple.mpegurl'
            : 'video/mp2t';

        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }

    /**
     * Stream file with HTTP Range support (for video seeking)
     */
    private function streamFileWithRange($path, Request $request)
    {
        $size = filesize($path);
        $length = $size;
        $start = 0;
        $end = $size - 1;

        header('Content-Type: video/mp4');
        header('Accept-Ranges: bytes');

        // Handle Range request (for seeking in video)
        if ($request->header('Range')) {
            $range = $request->header('Range');
            list(, $range) = explode('=', $range, 2);

            if (strpos($range, ',') !== false) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes */$size");
                exit;
            }

            if ($range == '-') {
                $start = $size - substr($range, 1);
            } else {
                $range = explode('-', $range);
                $start = $range[0];
                $end = (isset($range[1]) && is_numeric($range[1])) ? $range[1] : $end;
            }

            $end = ($end > $size - 1) ? $size - 1 : $end;

            if ($start > $end || $start > $size - 1 || $end >= $size) {
                header('HTTP/1.1 416 Requested Range Not Satisfiable');
                header("Content-Range: bytes */$size");
                exit;
            }

            $length = $end - $start + 1;

            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$size");
        }

        header("Content-Length: $length");

        // Stream file
        $fp = fopen($path, 'rb');
        fseek($fp, $start);

        $buffer = 1024 * 8; // 8KB buffer
        while (!feof($fp) && ($pos = ftell($fp)) <= $end) {
            if ($pos + $buffer > $end) {
                $buffer = $end - $pos + 1;
            }
            echo fread($fp, $buffer);
            flush();
        }

        fclose($fp);
        exit;
    }
}
