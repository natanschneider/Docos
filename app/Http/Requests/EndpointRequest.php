<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndpointRequest extends FormRequest
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
                'application_id' => ['required', 'string', 'exists:applications,id'],
                'columns' => ['array', 'exists:databases,id'],
            ],
            'GET' => [
                'id' => ['required_if:application_id,null', 'string', 'exists:endpoints,id'],
                'application_id' => ['required_if:id,null', 'string', 'exists:applications,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:endpoints,id'],
                'name' => ['string', 'max:255'],
                'application_id' => ['string', 'exists:applications,id'],
                'columns' => ['array', 'exists:databases,id'],
                'detach_columns' => ['array', 'exists:databases,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:endpoints,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:endpoints,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'application_id' => ['string', 'exists:applications,id'],
                'columns' => ['array', 'exists:databases,id'],
                'detach_columns' => ['array', 'exists:databases,id'],
            ]
        };
    }
}
