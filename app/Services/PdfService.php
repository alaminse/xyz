<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public function generatePdfWithWatermark($html, $filename)
    {
        $pdf = FacadePdf::loadHTML($html);
        $canvas = $pdf->getDomPDF()->getCanvas();
        $canvas->image(public_path('uploads/logo/watermark.png'), 100, 300, 400, 100);
        return $pdf->download($filename);
    }

    public function generatePdf($html, $filename)
{
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $canvas = $dompdf->getCanvas();

    // Path to the custom font
    $fontPath = public_path('font/DRAGRAVE.otf'); // Ensure this file exists
    if (!file_exists($fontPath)) {
        throw new \Exception("Font file not found at {$fontPath}");
    }

    // Register the font in Dompdf (it's necessary to pass the font name and the path)
    $fontName = 'DRAGRAVE';  // Use a custom name for the font
    $dompdf->getCanvas()->get_font($fontPath);

    // Watermark text settings
    $watermarkText = env('APP_NAME', 'Your App Name');
    $fontSize = 56;

    // Get the page count
    $pageCount = $canvas->get_page_count();

    for ($page = 1; $page <= $pageCount; $page++) {
        $canvasWidth = $canvas->get_width();
        $canvasHeight = $canvas->get_height();

        // Get the text width for centering the watermark
        $textWidth = $canvas->get_font_metrics()->getTextWidth($watermarkText, $fontName, $fontSize);

        // Position for centering text
        $xPos = ($canvasWidth - $textWidth) / 2;
        $yPos = $canvasHeight / 2;

        // Apply watermark text on each page
        $canvas->page_script(function ($pageCanvas) use ($xPos, $yPos, $watermarkText, $fontSize, $fontName) {
            $pageCanvas->text(
                $xPos,
                $yPos,
                $watermarkText,
                $fontName, // Use the registered font name
                $fontSize,
                [255, 165, 0, 0.1] // RGBA color for orange with 10% opacity
            );
        });
    }

    // Output the PDF to the browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    echo $dompdf->output();
    exit;
}



}
