<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

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
