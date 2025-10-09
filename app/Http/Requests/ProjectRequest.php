<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
                'company_id' => ['required', 'int', 'exists:companies,id'],
            ],
            'GET' => [
                'id' => ['required_if:company_id,null', 'int', 'exists:projects,id'],
                'company_id' => ['required_if:id,null', 'int', 'exists:companies,id'],
            ],
            'PUT' => [
                'id' => ['required', 'int', 'exists:projects,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['int', 'exists:companies,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'int', 'exists:projects,id'],
            ],
            default => [
                'id' => ['int', 'exists:projects,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['int', 'exists:companies,id'],
            ]
        };
    }
}
