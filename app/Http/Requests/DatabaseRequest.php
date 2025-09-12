<?php

declare(strict_types=1);

namespace App\Http\Requests;

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
}
