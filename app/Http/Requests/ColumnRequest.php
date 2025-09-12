<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColumnRequest extends FormRequest
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
                'table_id' => ['required', 'string', 'exists:tables,id'],
                'type_id' => ['required', 'string', 'exists:types,id'],
            ],
            'GET' => [
                'id' => ['required_if:table_id,null', 'string', 'exists:columns,id'],
                'table_id' => ['required_if:id,null', 'string', 'exists:tables,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'table_id' => ['string', 'exists:tables,id'],
                'type_id' => ['string', 'exists:types,id'],
                'uuid' => ['uuid', 'exists:columns,uuid'],
                'status' => ['boolean'],
            ]
        };
    }
}
