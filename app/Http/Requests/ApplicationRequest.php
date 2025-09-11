<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'DEFAULT' => [
                'id' => ['string', 'exists:applications,id'],
                'name' => ['string', 'max:255'],
                'project_id' => ['string', 'exists:projects,id'],
                'uuid' => ['uuid', 'exists:applications,uuid'],
                'status' => ['boolean'],
            ],
        };
    }
}
