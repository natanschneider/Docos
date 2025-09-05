<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;

class ProjectController extends Controller
{
    public function store(ProjectRequest $request): Project
    {
        return (new ProjectRepository())->create($request);
    }
}
