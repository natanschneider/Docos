<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
                'project_id' => ['required', 'string', 'exists:projects,id'],
                'databases' => ['array', 'exists:databases,id'],
            ],
            'GET' => [
                'id' => ['required_if:project_id,null', 'string', 'exists:applications,id'],
                'project_id' => ['required_if:id,null', 'string', 'exists:projects,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:applications,id'],
                'name' => ['string', 'max:255'],
                'project_id' => ['string', 'exists:projects,id'],
                'databases' => ['array', 'exists:databases,id'],
                'detach_databases' => ['array', 'exists:databases,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:applications,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:applications,id'],
                'name' => ['string', 'max:255'],
                'project_id' => ['string', 'exists:projects,id'],
                'databases' => ['array', 'exists:databases,id'],
                'detach_databases' => ['array', 'exists:databases,id'],
                'uuid' => ['uuid', 'exists:applications,uuid'],
                'status' => ['boolean'],
            ],
        };
    }
}
