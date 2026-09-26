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
        return [
            'file' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg,webp', 'max:20480'],
            'format' => ['required', 'string'],
            'typearchive' => ['required', 'string'],
            'description' => ['required', 'string', 'max:250'],
            'date_doc' => ['required', 'string'],
            'emplacement' => ['required', 'string'],
            'emplacement2' => ['required', 'string'],
            'rayon' => ['nullable', 'string'],
            'travee' => ['nullable', 'string'],
            'cote' => ['nullable', 'string'],
            'departement' => ['required', 'string'],
        ];
    }
}
