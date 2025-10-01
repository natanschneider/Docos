<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TableRequest;
use App\Models\Database;
use App\Models\Table;
use DB;
use Illuminate\Database\Eloquent\Collection;

final class TableRepository
{
    public function create(TableRequest $request): Table
    {
        return Table::create($request->all());
    }

    public function get(TableRequest $request): Collection
    {
        $query = Table::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('database_id')) {
            $query->where('database_id', $request->database_id);
        }

        $query->whereHas('database.company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->get();
    }

    public function update(TableRequest $request)
    {
        Table::where('id', $request->id)->update($request->all());

        return Table::where('id', $request->id)->first();
    }

    public function destroy(TableRequest $request): Table|Collection
    {
        return DB::transaction(function () use ($request) {
            $table = Table::find($request->id);

            $table->columns()->delete();
            $table->delete();

            if (Table::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $table;
        });
    }
}
