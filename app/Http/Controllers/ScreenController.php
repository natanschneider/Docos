<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;
use App\Repositories\ScreenRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class ScreenController extends Controller
{
    public function store(ScreenRequest $request): Screen
    {
        Gate::authorize('create', [Screen::class, $request]);

        return (new ScreenRepository())->create($request);
    }

    public function get(ScreenRequest $request): Collection
    {
        Gate::authorize('view', [Screen::class, $request]);

        return (new ScreenRepository())->get($request);
    }

    public function update(ScreenRequest $request): Screen
    {
        Gate::authorize('update', [Screen::findOrFail($request->id), $request]);

        return (new ScreenRepository())->update($request);
    }

    public function destroy(ScreenRequest $request): Screen
    {
        Gate::authorize('delete', Screen::findOrFail($request->id));

        return (new ScreenRepository())->destroy($request);
    }
}
