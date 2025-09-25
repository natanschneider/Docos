<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function store(ProjectRequest $request): Project
    {
        Gate::authorize('create', [Project::class, $request]);

        return (new ProjectRepository())->create($request);
    }

    public function get(ProjectRequest $request): Collection
    {
        Gate::authorize('view', [Project::class, $request]);

        return (new ProjectRepository())->get($request);
    }

    public function update(ProjectRequest $request): Collection
    {
        Gate::authorize('update', [Project::findOrFail($request->id), $request]);

        return (new ProjectRepository())->update($request);
    }

    public function destroy(ProjectRequest $request): Project|Collection
    {
        Gate::authorize('delete', [Project::findOrFail($request->id), $request]);

        return (new ProjectRepository())->destroy($request);
    }
}
