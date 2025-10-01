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
    public function create(ApplicationRequest $request): Application
    {
        return DB::transaction(function () use ($request) {
            $application = Application::create([
                'name' => $request->name,
                'project_id' => $request->project_id,
            ]);

            if ($request->has('databases')) {
                $application->databases()->attach($request->databases);
            }

            return $application->load('databases');
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

        $query->whereHas('project.company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->with('databases')->get();
    }

    public function update(ApplicationRequest $request): Application
    {
        if ($request->has('project_id')) {
            $project = Project::where('id', $request->project_id)->first();

            if ($request->user()->companies()->where('companies.id', $project->company_id)->doesntExist()) {
                abort(403, 'Project does not belong to user or does not exist');
            }
        }

        return DB::transaction(function () use ($request): Application {
            $application = Application::findOrFail($request->id);
            $application->update($request->only([
                'name',
                'project_id',
            ]));

            if ($request->has('databases')) {
                $application->databases()->syncWithoutDetaching($request->databases);
            }

            if ($request->has('detach_databases')) {
                $application->databases()->detach($request->detach_databases);
            }

            return $application->load('databases');
        });
    }

    public function destroy(ApplicationRequest $request): Application|Collection
    {
        return DB::transaction(function () use ($request) {
            $application = Application::find($request->id);

            $application->endpoints()->delete();
            $application->screens()->delete();
            $application->databases()->detach();
            $application->delete();

            if (Application::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $application;
        });
    }
}
