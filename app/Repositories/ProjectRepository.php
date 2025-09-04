<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;

final class ProjectRepository
{
    public function create(ProjectRequest $request): Project
    {
        return Project::create([
            'name' => $request->name,
            'company_id' => $request->company_id
        ]);
    }
}
