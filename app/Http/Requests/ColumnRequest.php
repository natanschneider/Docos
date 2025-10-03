<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

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
                'doc_file' => [File::types(['text/markdown'])->max('10mb')],
                'table_id' => ['required', 'string', 'exists:tables,id'],
                'type_id' => ['required', 'string', 'exists:types,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
            ],
            'GET' => [
                'id' => ['required_if:table_id,null', 'string', 'exists:columns,id'],
                'table_id' => ['required_if:id,null', 'string', 'exists:tables,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => [File::types(['text/markdown'])->max('10mb')],
                'table_id' => ['string', 'exists:tables,id'],
                'type_id' => ['string', 'exists:types,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'detach_related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:columns,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => [File::types(['text/markdown'])->max('10mb')],
                'table_id' => ['string', 'exists:tables,id'],
                'type_id' => ['string', 'exists:types,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'detach_related_columns' => ['array:fk,pk', 'exists:columns,id'],
                'indexed' => ['boolean'],
            ]
        };
    }
}
