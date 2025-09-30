<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TableRequest;
use App\Models\Table;
use App\Repositories\TableRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class TableController extends Controller
{
    public function store(TableRequest $request): Table
    {
        Gate::authorize('create', [Table::class, $request]);

        return (new TableRepository())->create($request);
    }

    public function get(TableRequest $request): Collection
    {
        Gate::authorize('view', [Table::class, $request]);

        return (new TableRepository())->get($request);
    }

    public function update(TableRequest $request): Table
    {
        Gate::authorize('update', [Table::findOrFail($request->id), $request]);

        return (new TableRepository())->update($request);
    }

    public function destroy(TableRequest $request): Table|Collection
    {
        Gate::authorize('delete', Table::findOrFail($request->id));

        return (new TableRepository())->destroy($request);
    }
}
