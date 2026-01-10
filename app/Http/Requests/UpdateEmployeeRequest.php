<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('employee'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');
        $companyId = $this->user()->company_id;
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($employee->id)
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'employee_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('employee_profiles', 'employee_id')
                    ->where('company_id', $companyId)
                    ->ignore($employee->employeeProfile?->id ?? 0)
            ],
            'department' => ['nullable', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'hire_date' => ['nullable', 'date'],
        ];

        // Only Admin can change roles
        if ($user->isAdmin()) {
            $rules['role'] = [
                'nullable',
                Rule::in(['employee', 'admin', 'supervisor']),
            ];
        }

        return $rules;
    }
}
