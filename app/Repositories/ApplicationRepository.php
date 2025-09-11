<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

final class ApplicationRepository
{
    public function create(ApplicationRequest $request): Application
    {
        return Application::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
        ]);
    }

    public function get(ApplicationRequest $request): Collection
    {
        $query = Application::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        return $query->get();
    }

    public function update(ApplicationRequest $request): Collection
    {
        if ($request->has('project_id')) {
            $project = Project::where('id', $request->project_id)->first();

            if ($request->user()->companies()->where('companies.id', $project->company_id)->doesntExist()) {
                abort(403, 'Project does not belong to user or does not exist');
            }
        }

        Application::where('id', $request->id)->update($request->all());

        return Application::where('id', $request->id)->get();
    }
}
