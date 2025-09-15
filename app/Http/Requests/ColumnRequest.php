<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Column;
use App\Models\Table;
use Illuminate\Foundation\Http\FormRequest;

class ColumnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->has('table_id')) {
            $database = Table::find($this->table_id)->database;

            if ($this->user()->companies()->where('companies.id', $database->company_id)->doesntExist()) {
                abort(403, 'Table does not belong to user or does not exist');
            }
        }

        return true;
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
                'doc_file' => ['string', 'max:500'],
                'table_id' => ['string', 'exists:tables,id'],
                'type_id' => ['string', 'exists:types,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:columns,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:columns,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'table_id' => ['string', 'exists:tables,id'],
                'type_id' => ['string', 'exists:types,id'],
                'constraints' => ['array', 'exists:constraints,id'],
                'detach_constraints' => ['array', 'exists:constraints,id'],
                'indexed' => ['boolean'],
                'uuid' => ['uuid', 'exists:columns,uuid'],
                'status' => ['boolean'],
            ]
        };
    }

    /**
     * Determine if the column id provided matches to a company that belongs to the user
     */
    public function unsureColumnBelongsToUser(self $request): void
    {
        $company = Column::find($request->id)->table->database->company;

        if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
            abort(403, 'Column does not belong to user or does not exist');
        }
    }
}
