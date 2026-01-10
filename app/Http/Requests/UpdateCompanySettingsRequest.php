<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Only Admin can update company settings.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $company = $user->company;
        
        // Only Admin can update their own company settings
        return $user && $company && $user->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'language' => ['required', 'in:es,en'],
            'timezone' => ['required', 'string', 'in:' . implode(',', timezone_identifiers_list())],
        ];
    }
}

