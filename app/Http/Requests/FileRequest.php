<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);

        return [
            'id' => ['required', 'string'],
            'doc_file' => [
                'required_if:_method,POST',
                'file',
                function ($attribute, $value, $fail): void {
                    $mimeType = $value->getMimeType();
                    $extension = $value->getClientOriginalExtension();

                    $acceptableMimeTypes = [
                        'text/markdown',
                        'text/x-markdown',
                        'application/x-markdown',
                        'text/plain',
                    ];

                    $acceptableExtensions = ['md', 'markdown'];

                    if (! in_array($mimeType, $acceptableMimeTypes)) {
                        $fail(sprintf('The %s must be a markdown file. Detected MIME type: %s', $attribute, $mimeType));
                    }

                    if (! in_array(mb_strtolower($extension), $acceptableExtensions)) {
                        $fail(sprintf('The %s must have an extension of .md', $attribute));
                    }
                },
                'max:36700160',
            ],
        ];
    }
}
