<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request): Application
    {
        Gate::authorize('create', [Application::class, $request]);

        return (new ApplicationRepository())->create($request);
    }

    public function get(ApplicationRequest $request): Collection
    {
        Gate::authorize('view', [Application::class, $request]);

        return (new ApplicationRepository())->get($request);
    }

    public function update(ApplicationRequest $request): Application
    {
        Gate::authorize('update', [Application::findOrFail($request->id), $request]);

        return (new ApplicationRepository())->update($request);
    }

    public function destroy(ApplicationRequest $request): Application|Collection
    {
        Gate::authorize('delete', Application::findOrFail($request->id));

        return (new ApplicationRepository())->destroy($request);
    }

    public function getLatest(Request $request, ?int $id = null): array
    {
        $application = (new ApplicationRepository())->getLatest($request, $id);
        $id = isset($application['id']) ? (int) $application['id'] : null;

        if ($id) {
            cookie()->queue('currentApplication', $id, 60);
        }

        return $application;
    }
}
