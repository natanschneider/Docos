<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Repositories\ColumnRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class ColumnController extends Controller
{
    public function store(ColumnRequest $request): Column
    {
        Gate::authorize('create', [Column::class, $request]);

        return new ColumnRepository()->create($request);
    }

    public function get(ColumnRequest $request): Collection
    {
        Gate::authorize('view', [Column::class, $request]);

        return new ColumnRepository()->get($request);
    }

    public function update(ColumnRequest $request): Column
    {
        Gate::authorize('update', [Column::findOrFail($request->id), $request]);

        return new ColumnRepository()->update($request);
    }

    public function destroy(ColumnRequest $request): Column|Collection
    {
        Gate::authorize('delete', Column::findOrFail($request->id));

        return new ColumnRepository()->destroy($request);
    }
}
