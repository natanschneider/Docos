<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectController extends Controller
{
    public function store(ProjectRequest $request): Project
    {
        return (new ProjectRepository())->create($request);
    }

    public function get(ProjectRequest $request): Collection
    {
        if ($request->has('id')) {
            (new ProjectRequest())->ensureProjectBelongsToUser($request);
        }

        return (new ProjectRepository())->get($request);
    }

    public function update(ProjectRequest $request): Collection
    {
        (new ProjectRequest())->ensureProjectBelongsToUser($request);

        return (new ProjectRepository())->update($request);
    }
}
