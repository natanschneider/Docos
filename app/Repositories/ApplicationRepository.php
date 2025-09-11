<?php

declare(strict_types=1);

namespace App\Repositories;

final class ApplicationRepository
{
    public function create(ApplicationRequest $request): Application
    {
        return Application::create([
            'name' => $request->name,
            'project_id' => $request->project_id,
        ]);
    }
}
