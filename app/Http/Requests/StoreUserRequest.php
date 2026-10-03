<?php

namespace App\Http\Requests;

use App\Models\Department;
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
        return $this->user() && $this->user()->isSuper();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $role = (string) $this->input('roles');

        $isSuper = str_contains(strtolower($role), 'super');
        $isPrivileged = ! $isSuper && (str_contains(strtolower($role), 'privilég') || str_contains(strtolower($role), 'privileg'));
        $isClassique = ! $isSuper && ! $isPrivileged;

        $deptRule = $isSuper ? ['nullable', 'exists:departments,id'] : ['required', 'exists:departments,id'];
        $subDeptRule = $isClassique ? ['required', 'exists:departments,id'] : ['nullable', 'exists:departments,id'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'matricule' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'phone' => ['nullable', 'string', 'max:255'],
            'roles' => ['required', 'string', Rule::in(['classique', 'privilégié', 'super privilégé'])],
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
