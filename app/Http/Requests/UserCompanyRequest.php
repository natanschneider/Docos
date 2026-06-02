<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Http\Middleware\HandleSelectedCompany;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserCompanyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => [
                'company_id' => ['required', 'exists:companies,id'],
                'user_email' => ['required', 'exists:users,email'],
            ],
            'GET' => [
                'company_id' => ['required', 'exists:companies,id'],
            ],
            'DELETE' => [
                'company_id' => ['required', 'exists:companies,id'],
                'user_email' => ['required', 'exists:users,email'],
            ],
            'DEFAULT' => [
                'company_id' => ['exists:companies,id'],
                'user_email' => ['exists:users,email'],
            ]
        };
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('company_id')) {
            $this->merge([
                'company_id' => (new HandleSelectedCompany)->handle($this),
            ]);
        }
    }
}
