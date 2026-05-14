<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SecurePdf;
use App\Services\SecurePdfService;
use Illuminate\Http\Request;

class SecurePdfViewController extends Controller
{
    public function __construct(private SecurePdfService $service) {}

    public function index()
    {
        $pdfs = SecurePdf::where('is_active', true)->latest()->paginate(12);
        return view('frontend.secure-pdfs.index', compact('pdfs'));
    }

    public function view(Request $request, string $slug)
    {
        $pdf   = SecurePdf::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        $token = $this->service->generateViewToken($pdf, $request);
        $this->service->logAccess($pdf->id, $request->user()->id, $request, 'viewed');
        return view('frontend.secure-pdfs.viewer', compact('pdf', 'token'));
    }
}
