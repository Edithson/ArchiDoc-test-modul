<?php

namespace App\Http\Requests;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->hasPermission('User', 'create');
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
        $roleId = $this->input('role_id');
        $roleObj = $roleId ? Role::find($roleId) : null;
        $roleName = $roleObj ? strtolower($roleObj->name) : '';

        $isSuper = str_contains($roleName, 'super');
        $isClassique = str_contains($roleName, 'classic') || str_contains($roleName, 'classique');

        $deptRule = $isSuper ? ['nullable', 'exists:departments,id'] : ['required', 'exists:departments,id'];
        $subDeptRule = $isClassique ? ['required', 'exists:departments,id'] : ['nullable', 'exists:departments,id'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'matricule' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => $deptRule,
            'sub_department_id' => $subDeptRule,
            'statut' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $currentUser = $this->user();
            $departmentId = $this->input('department_id');
            $subDepartmentId = $this->input('sub_department_id');
            $roleId = $this->input('role_id');

            if ($currentUser && ! $currentUser->isSuper()) {
                // Non-super users cannot assign Super Privileged role
                if ($roleId) {
                    $roleObj = Role::find($roleId);
                    if ($roleObj && str_contains(strtolower($roleObj->name), 'super')) {
                        $validator->errors()->add('role_id', 'Seul un Super Privilégié peut attribuer le rôle Super Privilégié.');
                    }
                }

                // Department constraint
                if ($currentUser->department_id && (int) $departmentId !== (int) $currentUser->department_id) {
                    $validator->errors()->add('department_id', 'Vous ne pouvez créer des utilisateurs que dans votre propre département.');
                }

                if ($currentUser->sub_department_id && (int) $subDepartmentId !== (int) $currentUser->sub_department_id) {
                    $validator->errors()->add('sub_department_id', 'Vous ne pouvez créer des utilisateurs que dans votre propre sous-département.');
                }
            }

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
