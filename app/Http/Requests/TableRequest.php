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
                'doc_file' => ['string', 'max:500'],
                'database_id' => ['required', 'string', 'exists:databases,id'],
            ],
            'GET' => [
                'id' => ['required_if:database_id,null', 'string', 'exists:tables,id'],
                'database_id' => ['required_if:id,null', 'string', 'exists:databases,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:tables,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'database_id' => ['string', 'exists:databases,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:tables,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:tables,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'database_id' => ['string', 'exists:databases,id'],
                'uuid' => ['uuid', 'exists:tables,uuid'],
                'status' => ['boolean'],
            ]
        };
    }
}
