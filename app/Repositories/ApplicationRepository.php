<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Application;
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
}
