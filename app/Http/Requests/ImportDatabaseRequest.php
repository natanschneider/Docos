<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportDatabaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => [
                'file' => ['required', 'file', 'mimetypes:application/octet-stream,text/plain,application/sql'],
                'database_id' => ['required', 'int', 'exists:databases,id']
            ]
        };
    }
}
