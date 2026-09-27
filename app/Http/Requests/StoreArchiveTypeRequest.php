<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArchiveTypeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:archive_types,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'dua' => ['nullable', 'integer', 'min:0', 'max:999'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du type d\'archive est obligatoire.',
            'name.unique' => 'Ce type d\'archive existe déjà.',
            'dua.integer' => 'La DUA doit être un nombre entier d\'années.',
        ];
    }
}
