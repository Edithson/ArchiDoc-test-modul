<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
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
            'remove_files' => ['nullable', 'array'],
            'remove_files.*' => ['nullable', 'string'],
            'pieces' => ['nullable', 'array'],
            'pieces.*' => ['nullable'],
            'files' => ['nullable', 'array'],
            'files.*' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        return;
                    }
                    $files = is_array($value) ? $value : [$value];
                    foreach ($files as $file) {
                        if (! $file || ! ($file instanceof UploadedFile)) {
                            continue;
                        }
                        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                        if (! in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'], true)) {
                            $fail('Les pièces jointes doivent être au format PDF ou image (JPG, PNG).');

                            return;
                        }
                        if ($file->getSize() > 5120 * 1024) {
                            $fail('La taille maximale de chaque fichier est de 5 Mo.');

                            return;
                        }
                    }
                },
            ],
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
        ];
    }
}
