<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class ProjectRepository
{
    public function create(ProjectRequest $request): Project
    {
        return Project::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
        ]);
    }

    public function get(ProjectRequest $request): Collection
    {
        $query = Project::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $query->whereHas('company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->get();
    }

    public function update(ProjectRequest $request): Collection
    {
        Project::where('id', $request->id)->update($request->only([
            'name',
            'company_id',
        ]));

        return Project::where('id', $request->id)->get();
    }

    public function destroy(ProjectRequest $request): Project|Collection
    {
        return DB::transaction(function () use ($request) {
            $project = Project::find($request->id);

            $project->applications()->delete();
            $project->delete();

            if (Project::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $project;
        });
    }

    public function getLatest(Request|ProjectRequest $request, ?int $id = null): array
    {
        if ($id) {
            $projectId = Project::where('company_id', $request->cookie('currentCompany'))
                ->where('id', $id)
                ->first();

            if ($projectId) {
                return $projectId->toArray();
            }
        }

        $project = Project::where('company_id', $request->cookie('currentCompany'))
            ->latest()
            ->first();

        return $project?->toArray() ?? [ 'id' => null ];
    }
}
