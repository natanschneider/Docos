<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColumnRequest extends FormRequest
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
                'table_id' => ['required', 'int', 'exists:tables,id'],
                'type_id' => ['required', 'int', 'exists:types,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
            ],
            'GET' => [
                'id' => ['required_if:table_id,null', 'int', 'exists:columns,id'],
                'table_id' => ['required_if:id,null', 'int', 'exists:tables,id'],
            ],
            'PUT' => [
                'id' => ['required', 'int', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'table_id' => ['int', 'exists:tables,id'],
                'type_id' => ['int', 'exists:types,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'detach_related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
            ],
            'DELETE' => [
                'id' => ['required', 'int', 'exists:columns,id'],
            ],
            'DEFAULT' => [
                'id' => ['int', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'table_id' => ['int', 'exists:tables,id'],
                'type_id' => ['int', 'exists:types,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'detach_related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'indexed' => ['boolean'],
            ]
        };
    }
}
