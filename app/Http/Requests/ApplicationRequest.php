<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Application;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (isset($this->project_id) && $this->user()->companies()->where('projects.id', $this->project_id)->doesntExist()) {
            abort(403, 'Project does not belong to user or does not exist');
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
                'project_id' => ['required', 'string', 'exists:projects,id'],
            ],
            'GET' => [
                'id' => ['required_if:project_id,null', 'string', 'exists:applications,id'],
                'project_id' => ['required_if:id,null', 'string', 'exists:projects,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:applications,id'],
                'name' => ['string', 'max:255'],
                'project_id' => ['string', 'exists:projects,id'],
            ],
            'DEFAULT' => [
                'id' => ['string', 'exists:applications,id'],
                'name' => ['string', 'max:255'],
                'project_id' => ['string', 'exists:projects,id'],
                'uuid' => ['uuid', 'exists:applications,uuid'],
                'status' => ['boolean'],
            ],
        };
    }

    /**
     * Determine if the application id provided matches to a company that belongs to the user
     */
    public function ensureApplicationBelongsToUser(self $request): void
    {
        $application = Application::where('id', $request->id)->first();

        if ($request->user()->companies()->where('projects.id', $application->project_id)->doesntExist()) {
            abort(403, 'Application does not belong to user or does not exist');
        }
    }
}
