<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
                'description' => ['required', 'string', 'max:255'],
                'status' => ['boolean'],
            ],
            'GET' => [
                'id' => ['string', 'exists:companies,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:companies,id'],
                'name' => ['string', 'max:255'],
                'description' => ['string', 'max:255'],
                'status' => ['boolean'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:companies,id'],
            ],
            default => [
                'id' => ['string', 'exists:companies,id'],
                'name' => ['string', 'max:255'],
                'description' => ['string', 'max:255'],
                'status' => ['boolean'],
            ],
        };
    }

    public function ensureCompanyBelongsToUser(self $request): void
    {
        if ($request->user()->companies()->where('companies.id', $request->id)->doesntExist()) {
            abort(403);
        }
    }
}
