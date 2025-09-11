<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Application;
use App\Models\Screen;
use Illuminate\Foundation\Http\FormRequest;

class ScreenRequest extends FormRequest
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
                'id' => ['required_if:application_id,null', 'string', 'exists:screens,id'],
                'application_id' => ['required_if:id,null', 'string', 'exists:applications,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:screens,id'],
                'name' => ['string', 'max:255'],
                'application_id' => ['string', 'exists:applications,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:screens,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:screens,id'],
                'name' => ['string', 'max:255'],
                'doc_file' => ['string', 'max:500'],
                'application_id' => ['string', 'exists:applications,id'],
                'uuid' => ['uuid', 'exists:screens,uuid'],
                'status' => ['boolean'],
            ]
        };
    }

    /**
     * Determine if the screen id provided matches to a company that belongs to the user
     */
    public function ensureScreenBelongsToUser(self $request): void
    {
        $company = Screen::find($request->id)->application->project->company;

        if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
            abort(403, 'Screen does not belong to user or does not exist');
        }
    }
}
