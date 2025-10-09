<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScreenRequest extends FormRequest
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
                'application_id' => ['required', 'int', 'exists:applications,id'],
                'columns' => ['array', 'exists:columns,id'],
            ],
            'GET' => [
                'id' => ['required_if:application_id,null', 'int', 'exists:screens,id'],
                'application_id' => ['required_if:id,null', 'int', 'exists:applications,id'],
            ],
            'PUT' => [
                'id' => ['required', 'int', 'exists:screens,id'],
                'name' => ['string', 'max:255'],
                'application_id' => ['int', 'exists:applications,id'],
                'columns' => ['array', 'exists:columns,id'],
                'detach_columns' => ['array', 'exists:columns,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'int', 'exists:screens,id'],
            ],
            'DEFAULT' => [
                'id' => ['int', 'exists:screens,id'],
                'name' => ['string', 'max:255'],
                'application_id' => ['int', 'exists:applications,id'],
                'columns' => ['array', 'exists:columns,id'],
                'detach_columns' => ['array', 'exists:columns,id'],
            ]
        };
    }
}
