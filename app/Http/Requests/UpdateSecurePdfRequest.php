<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSecurePdfRequest extends FormRequest
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
            'total_pages' => 'nullable|integer|min:0',
            'pdf_file'    => 'nullable|file|mimes:pdf|max:51200',
            'is_active'   => 'nullable|in:0,1',
            'allow_print' => 'nullable|in:0,1',
        ];
    }
}
