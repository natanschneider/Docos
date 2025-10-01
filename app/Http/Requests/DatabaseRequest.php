<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatabaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => [
                'name' => ['required', 'string', 'max:255'],
                'company_id' => ['required', 'string', 'exists:companies,id'],
                'engine_id' => ['required', 'string', 'exists:engines,id'],
            ],
            'GET' => [
                'id' => ['required_if:company_id,null', 'string', 'exists:databases,id'],
                'company_id' => ['required_if:id,null', 'string', 'exists:companies,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:databases,id'],
                'name' => ['string', 'max:255'],
                'engine_id' => ['string', 'exists:engines,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:databases,id'],
            ],
            default => [
                'id' => ['string', 'exists:databases,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['string', 'exists:companies,id'],
                'engine_id' => ['string', 'exists:engines,id'],
            ]
        };
    }
}
