<?php

namespace App\Http\Requests;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isSuper();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('role_id') && $this->has('roles')) {
            $roleInput = (string) $this->input('roles');
            $roleObj = is_numeric($roleInput)
                ? Role::find($roleInput)
                : Role::findByName($roleInput);

            if (! $roleObj && is_string($roleInput) && filled($roleInput)) {
                $normalized = strtolower(trim($roleInput));
                $roleName = 'Classic';
                if (str_contains($normalized, 'super')) {
                    $roleName = 'Super privilégié';
                } elseif (str_contains($normalized, 'privilég') || str_contains($normalized, 'privileg')) {
                    $roleName = 'Privilégié';
                }

                $roleObj = Role::create([
                    'name' => $roleName,
                    'permissions' => Role::defaultPermissionsFor($roleName),
                ]);
            }

            if ($roleObj) {
                $this->merge(['role_id' => $roleObj->id]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('user');
        $roleId = $this->input('role_id');
        $roleObj = $roleId ? Role::find($roleId) : null;
        $roleName = $roleObj ? strtolower($roleObj->name) : '';

        $isSuper = str_contains($roleName, 'super');
        $isPrivileged = ! $isSuper && (str_contains($roleName, 'privilég') || str_contains($roleName, 'privileg'));
        $isClassique = ! $isSuper && ! $isPrivileged;

        $deptRule = $isSuper ? ['nullable', 'exists:departments,id'] : ['required', 'exists:departments,id'];
        $subDeptRule = $isClassique ? ['required', 'exists:departments,id'] : ['nullable', 'exists:departments,id'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'matricule' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => $deptRule,
            'sub_department_id' => $subDeptRule,
            'statut' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
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
