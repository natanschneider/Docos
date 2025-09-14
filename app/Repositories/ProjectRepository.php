<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
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

        return $query->get();
    }

    public function update(ProjectRequest $request): Collection
    {
        if ($request->has('company_id') && $request->user()->companies()->where('companies.id', $request->company_id)->doesntExist()) {
            abort(403, 'Provided company does not belong to user');
        }

        Project::where('id', $request->id)->update($request->all());

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
}
