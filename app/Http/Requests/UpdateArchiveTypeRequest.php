<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArchiveTypeRequest extends FormRequest
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
        $archiveType = $this->route('archive_type');
        $typeId = is_object($archiveType) ? $archiveType->id : $archiveType;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('archive_types', 'name')->ignore($typeId)],
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
            'name.unique' => 'Ce nom de type d\'archive est déjà utilisé par un autre enregistrement.',
            'dua.integer' => 'La DUA doit être un nombre entier d\'années.',
        ];
    }
}
