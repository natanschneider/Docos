<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Database;
use App\Models\Table;
use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->has('database_id')) {
            $database = Database::find($this->database_id);

            if ($this->user()->companies()->where('companies.id', $database->company_id)->doesntExist()) {
                abort(403, 'Database does not belong to user or does not exist');
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

    /**
     * Determine if the table id provided matches to a company that belongs to the user
     */
    public function ensureTableBelongsToUser(self $request): void
    {
        $database = Table::find($request->id)->database;

        if ($request->user()->companies()->where('companies.id', $database->company_id)->doesntExist()) {
            abort(403, 'Table does not belong to user or does not exist');
        }
    }
}
