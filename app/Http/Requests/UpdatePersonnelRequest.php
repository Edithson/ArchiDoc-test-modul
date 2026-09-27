<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePersonnelRequest extends FormRequest
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
        $personnel = $this->route('personnel');
        $personnelId = is_object($personnel) ? $personnel->id : $personnel;

        return [
            'name' => ['required', 'string', 'max:255'],
            'matricule' => ['required', 'string', 'max:255', Rule::unique('personnels', 'matricule')->ignore($personnelId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('personnels', 'email')->ignore($personnelId)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'pieces' => ['nullable', 'array'],
            'pieces.*' => ['nullable'],
            'files' => ['nullable', 'array'],
            'files.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
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
            'name.required' => 'Le nom et prénom de l\'agent sont obligatoires.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule appartient déjà à un autre agent.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'files.*.mimes' => 'Les pièces jointes doivent être au format PDF ou image (JPG, PNG).',
            'files.*.max' => 'La taille maximale d\'un fichier est de 5 Mo.',
        ];
    }
}
