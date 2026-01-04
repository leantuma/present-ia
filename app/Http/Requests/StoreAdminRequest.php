<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // If company_id is in route (from CompanyController), it's not required in request
        $companyId = $this->route('company')?->id ?? $this->input('company_id');
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'company_id' => $companyId ? ['nullable', 'exists:companies,id'] : ['required', 'exists:companies,id'],
        ];
    }
    
    protected function prepareForValidation()
    {
        // If creating from CompanyController, set company_id from route
        if ($this->route('company')) {
            $this->merge([
                'company_id' => $this->route('company')->id,
            ]);
        }
    }
}
