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
                'constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],

                'related_columns' => ['array'],
                'related_columns.pk' => ['array'],
                'related_columns.pk.*' => ['nullable', 'exists:columns,id'],
                'related_columns.fk' => ['array'],
                'related_columns.fk.*' => ['nullable', 'exists:columns,id'],
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
                'constraints' => ['array', 'exists:constraints,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],

                'related_columns' => ['array'],
                'related_columns.pk' => ['array'],
                'related_columns.pk.*' => ['nullable', 'exists:columns,id'],
                'related_columns.fk' => ['array'],
                'related_columns.fk.*' => ['nullable', 'exists:columns,id'],

                'detach_related_columns' => ['array'],
                'detach_related_columns.pk' => ['array'],
                'detach_related_columns.pk.*' => ['nullable', 'exists:columns,id'],
                'detach_related_columns.fk' => ['array'],
                'detach_related_columns.fk.*' => ['nullable', 'exists:columns,id'],
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
                'indexed' => ['boolean'],

                'related_columns' => ['array'],
                'related_columns.pk' => ['array'],
                'related_columns.pk.*' => ['nullable', 'exists:columns,id'],
                'related_columns.fk' => ['array'],
                'related_columns.fk.*' => ['nullable', 'exists:columns,id'],

                'detach_related_columns' => ['array'],
                'detach_related_columns.pk' => ['array'],
                'detach_related_columns.pk.*' => ['nullable', 'exists:columns,id'],
                'detach_related_columns.fk' => ['array'],
                'detach_related_columns.fk.*' => ['nullable', 'exists:columns,id'],
            ]
        };
    }
}
