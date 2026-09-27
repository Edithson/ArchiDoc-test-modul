<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePieceRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:pieces,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'obligatory' => ['required', 'boolean'],
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
            'name.required' => 'Le nom de la pièce est obligatoire.',
            'name.unique' => 'Une pièce portant ce nom existe déjà.',
            'obligatory.required' => 'Veuillez préciser si la pièce est obligatoire ou facultative.',
        ];
    }
}
