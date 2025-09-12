<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Database;
use Illuminate\Foundation\Http\FormRequest;

class DatabaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (isset($this->company_id) && $this->user()->companies()->where('companies.id', $this->company_id)->doesntExist()) {
            abort(403, 'Company does not belong to user or does not exist');
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
                'company_id' => ['required', 'string', 'exists:companies,id'],
                'engine_id' => ['required', 'string', 'exists:engines,id'],
            ],
            'GET' => [
                'id' => ['required_if:company_id,null', 'string', 'exists:databases,id'],
                'company_id' => ['required_if:id,null', 'string', 'exists:companies,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:databases,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['string', 'exists:companies,id'],
                'engine_id' => ['string', 'exists:engines,id'],
            ],
            default => [
                'id' => ['string', 'exists:databases,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['string', 'exists:companies,id'],
                'engine_id' => ['string', 'exists:engines,id'],
                'uuid' => ['uuid', 'exists:databases,uuid'],
                'status' => ['boolean'],
            ]
        };
    }

    /**
     * Determine if the id provided matches a company that belongs to the user
     * If id and company_id provided, check if database belongs to given company
     */
    public function ensureDatabaseBelongsToUser(self $request): void
    {
        $database = Database::where('id', $request->id)->first();

        if ($request->has('company_id') && $request->company_id !== (string) $database->company_id) {
            abort(403, 'Database does not belong to provided company or does not exist');
        }

        if ($request->user()->companies()->where('companies.id', $database->company_id)->doesntExist()) {
            abort(403, 'Database does not belong to user or does not exist');
        }
    }
}
