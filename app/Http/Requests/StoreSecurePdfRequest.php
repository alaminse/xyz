<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSecurePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:3000',
            'category'    => 'nullable|string|max:100',
            'total_pages' => 'required|integer|min:1',
            'pdf_file'    => 'required|file|mimes:pdf|max:51200',
            'is_active'   => 'nullable|in:0,1',
            'allow_print' => 'nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Title is required.',
            'total_pages.required' => 'Total pages is required.',
            'total_pages.min'      => 'Total pages must be at least 1.',
            'pdf_file.required'    => 'Please upload a PDF file.',
            'pdf_file.mimes'       => 'Only PDF files are allowed.',
            'pdf_file.max'         => 'PDF must not exceed 50MB.',
        ];
    }
}
