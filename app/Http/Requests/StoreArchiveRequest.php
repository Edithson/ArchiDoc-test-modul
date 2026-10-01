<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArchiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $maxKb = (int) setting('archivage.max_upload_size_mb', 20) * 1024;
        $allowedString = (string) setting('archivage.allowed_extensions', 'pdf, docx, xlsx, png, jpg, zip');
        $extensions = array_filter(array_map('trim', explode(',', str_replace(['.', ' '], ['', ''], $allowedString))));
        $mimesRule = ! empty($extensions) ? 'mimes:'.implode(',', $extensions) : 'mimes:pdf,docx,xlsx,png,jpg,zip';

        return [
            'file' => ['required', 'file', $mimesRule, "max:{$maxKb}"],
            'format' => ['required', 'string'],
            'archive_type_id' => ['required', 'exists:archive_types,id'],
            'description' => ['required', 'string', 'max:250'],
            'date_doc' => ['required', 'string'],
            'emplacement' => ['required', 'string'],
            'emplacement2' => ['required', 'string'],
            'rayon' => ['nullable', 'string'],
            'travee' => ['nullable', 'string'],
            'cote' => ['nullable', 'string'],
            'department_id' => ['required', 'exists:departments,id'],
        ];
    }
}
