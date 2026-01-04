<?php

namespace App\Http\Requests;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class StoreLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Leave::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();
        $companyId = $user->company_id;
        
        // If admin/supervisor, they can select any employee from their company
        // If employee, user_id is automatically set to themselves
        $userId = $this->input('user_id', $user->id);
        
        // Validate that the selected user belongs to the same company
        if ($user->isAdmin() || $user->isSupervisor()) {
            $employeeValidation = [
                'user_id' => [
                    'required',
                    'exists:users,id',
                    Rule::exists('users', 'id')->where('company_id', $companyId)->where('role', 'employee'),
                ],
            ];
        } else {
            $employeeValidation = [];
            $userId = $user->id; // Employee can only request for themselves
        }

        return array_merge($employeeValidation, [
            'type' => ['required', 'in:vacation,sick,maternity,other'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $companyId = $user->company_id;
            $userId = $this->input('user_id', $user->id);
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');

            if ($startDate && $endDate) {
                // Check for overlapping approved leaves
                $overlapping = Leave::where('company_id', $companyId)
                    ->where('user_id', $userId)
                    ->where('status', 'approved')
                    ->where(function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($q) use ($startDate, $endDate) {
                                $q->where('start_date', '<=', $startDate)
                                  ->where('end_date', '>=', $endDate);
                            });
                    })
                    ->exists();

                if ($overlapping) {
                    $validator->errors()->add('start_date', 'Ya existe una licencia aprobada que se solapa con las fechas seleccionadas.');
                }
            }
        });
    }
}
