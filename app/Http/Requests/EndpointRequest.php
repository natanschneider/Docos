<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

class EndpointRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->has('application_id')) {
            $application = Application::find($this->application_id)->project;

            if ($this->user()->companies()->where('companies.id', $application->company_id)->doesntExist()) {
                abort(403, 'Application does not belong to user or does not exist');
            }
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
                'application_id' => ['required', 'string', 'exists:applications,id'],
            ],
            'GET' => [
                'id' => ['required_if:application_id,null', 'string', 'exists:endpoints,id'],
                'application_id' => ['required_if:id,null', 'string', 'exists:applications,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:endpoints,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'application_id' => ['string', 'exists:applications,id'],
                'uuid' => ['uuid', 'exists:endpoints,uuid'],
                'status' => ['boolean'],
            ]
        };
    }
}
