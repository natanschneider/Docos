<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

final class TokenRequest extends LoginRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'token_name' => ['required', 'string'],
        ];
    }
}
