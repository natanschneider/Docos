<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ApplicationRepository
{
    public function create(ApplicationRequest $request): Collection
    {
        return DB::transaction(function () use ($request) {
            $application = Application::create([
                'name' => $request->name,
                'project_id' => $request->project_id,
            ]);

            if ($request->has('databases')) {
                $application->databases()->attach($request->databases);
            }

            return $application->with('databases')->get();
        });
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

        return $query->with('databases')->get();
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

    public function destroy(ApplicationRequest $request): Application|Collection
    {
        return DB::transaction(function () use ($request) {
            $application = Application::find($request->id);

            $application->endpoints()->delete();
            $application->screens()->delete();
            $application->delete();

            if (Application::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $application;
        });
    }
}
