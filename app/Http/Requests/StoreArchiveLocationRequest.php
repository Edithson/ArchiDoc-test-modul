<?php

namespace App\Http\Requests;

use App\Models\ArchiveLocation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreArchiveLocationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:archive_locations,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'integer', 'in:'.ArchiveLocation::TYPE_PHYSICAL.','.ArchiveLocation::TYPE_VIRTUAL],
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
            'name.required' => 'Le nom ou sigle de l\'emplacement est obligatoire.',
            'name.unique' => 'Cet emplacement existe déjà dans le système.',
            'type.required' => 'Le type d\'emplacement (Physique ou Virtuel) est obligatoire.',
            'type.in' => 'Le type d\'emplacement sélectionné est invalide.',
        ];
    }
}
