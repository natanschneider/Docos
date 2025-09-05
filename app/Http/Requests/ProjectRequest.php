<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (isset($this->company_id) && $this->user()->companies()->where('companies.id', $this->company_id)->doesntExist()) {
            abort(403, 'Company does not belong to user or does not exist');
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
                'company_id' => ['required', 'string', 'exists:companies,id'],
            ],
            'GET' => [
                'id' => ['required_if:company_id,null', 'string', 'exists:projects,id'],
                'company_id' => ['required_if:id,null', 'string', 'exists:companies,id'],
            ],
            'PUT' => [
                'id' => ['required', 'string', 'exists:projects,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['string', 'exists:companies,id'],
            ],
            'DELETE' => [
                'id' => ['required', 'string', 'exists:projects,id'],
            ],
            default => [
                'id' => ['string', 'exists:projects,id'],
                'name' => ['string', 'max:255'],
                'company_id' => ['string', 'exists:companies,id'],
                'uuid' => ['uuid', 'exists:projects,uuid'],
                'status' => ['boolean'],
            ]
        };
    }

    /**
     * Determine if the id provided matches a company that belongs to the user
     * If id and company_id provided, check if company belongs to given company
     */
    public function ensureProjectBelongsToUser(self $request): void
    {
        $project = Project::where('id', $request->id)->first();

        if ($request->has('company_id') && $request->company_id !== (string) $project->company_id) {
            abort(403, 'Project does not belong to provided company or does not exist');
        }

        if ($request->user()->companies()->where('companies.id', $project->company_id)->doesntExist()) {
            abort(403, 'Project does not belong to user or does not exist');
        }
    }
}
