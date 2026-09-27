<?php

namespace App\Http\Requests;

use App\Models\ArchiveLocation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArchiveLocationRequest extends FormRequest
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
        $locationParam = $this->route('archive_location');
        $locationId = is_object($locationParam) ? $locationParam->id : $locationParam;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('archive_locations', 'name')->ignore($locationId)],
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
            'name.required' => 'Le nom de l\'emplacement est obligatoire.',
            'name.unique' => 'Ce nom d\'emplacement est déjà utilisé.',
            'type.required' => 'Le type d\'emplacement est obligatoire.',
            'type.in' => 'Le type d\'emplacement est invalide.',
        ];
    }
}
