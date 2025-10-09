<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
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
                'database_id' => ['required', 'int', 'exists:databases,id'],
            ],
            'GET' => [
                'id' => ['required_if:database_id,null', 'int', 'exists:tables,id'],
                'database_id' => ['required_if:id,null', 'int', 'exists:databases,id'],
            ],
            'PUT' => [
                'id' => ['required', 'int', 'exists:tables,id'],
                'name' => ['string', 'max:255'],
                'database_id' => ['int', 'exists:databases,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'int', 'exists:tables,id'],
            ],
            'DEFAULT' => [
                'id' => ['int', 'exists:tables,id'],
                'name' => ['string', 'max:255'],
                'database_id' => ['int', 'exists:databases,id'],
            ]
        };
    }
}
