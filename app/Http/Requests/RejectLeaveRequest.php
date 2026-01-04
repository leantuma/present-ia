<?php

namespace App\Http\Requests;

use App\Models\Leave;
use Illuminate\Foundation\Http\FormRequest;

class RejectLeaveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $leave = $this->route('leave');
        return $this->user()->can('reject', $leave);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
