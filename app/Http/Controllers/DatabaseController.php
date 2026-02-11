<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use App\Repositories\DatabaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DatabaseController extends Controller
{
    public function store(DatabaseRequest $request): Database
    {
        Gate::authorize('create', [Database::class, $request]);

        return (new DatabaseRepository())->create($request);
    }

    public function get(DatabaseRequest $request): Collection
    {
        Gate::authorize('view', [Database::class, $request]);

        return (new DatabaseRepository())->get($request);
    }

    public function update(DatabaseRequest $request): Collection
    {
        Gate::authorize('update', Database::findOrFail($request->id));

        return (new DatabaseRepository())->update($request);
    }

    public function destroy(DatabaseRequest $request): Database|Collection
    {
        Gate::authorize('delete', Database::findOrFail($request->id));

        return (new DatabaseRepository())->destroy($request);
    }

    public function getLatest(Request $request, ?int $id = null)
    {
        $database = (new DatabaseRepository())->getLatest($request, $id);
        $id = isset($database['id']) ? (int) $database['id'] : null;

        if ($id) {
            cookie()->queue('currentDatabase', $id, 60);
        }

        return $database;
    }
}
