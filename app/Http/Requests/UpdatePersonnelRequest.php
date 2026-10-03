<?php

namespace App\Http\Requests;

use App\Models\Department;
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
            'department_id' => ['nullable', 'exists:departments,id'],
            'sub_department_id' => ['nullable', 'exists:departments,id'],
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
                    $maxMb = (int) setting('archivage.max_upload_size_mb', 20);
                    $allowedString = (string) setting('archivage.allowed_extensions', 'pdf, docx, xlsx, png, jpg, zip');
                    $allowedExts = array_filter(array_map('trim', explode(',', str_replace(['.', ' '], ['', ''], strtolower($allowedString)))));

                    $files = is_array($value) ? $value : [$value];
                    foreach ($files as $file) {
                        if (! $file || ! ($file instanceof UploadedFile)) {
                            continue;
                        }
                        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension());
                        if (! empty($allowedExts) && ! in_array($extension, $allowedExts, true)) {
                            $fail("Le fichier a une extension non autorisée ({$extension}). Extensions autorisées : {$allowedString}.");

                            return;
                        }
                        if ($file->getSize() > $maxMb * 1024 * 1024) {
                            $fail("La taille maximale de chaque fichier est de {$maxMb} Mo.");

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

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $departmentId = $this->input('department_id');
            $subDepartmentId = $this->input('sub_department_id');

            if ($subDepartmentId) {
                $subDept = Department::find($subDepartmentId);
                if ($subDept && $subDept->parent_id) {
                    if ($departmentId && (int) $departmentId !== (int) $subDept->parent_id) {
                        $validator->errors()->add('sub_department_id', 'Le sous-département choisi n\'appartient pas à la Direction Principale sélectionnée.');
                    }
                }
            }
        });
    }
}
