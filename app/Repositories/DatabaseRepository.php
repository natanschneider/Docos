<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class DatabaseRepository
{
    public function create(DatabaseRequest $request): Database
    {
        return Database::create($request->only([
            'name',
            'company_id',
            'engine_id',
        ]));
    }

    public function get(DatabaseRequest $request): Collection
    {
        $query = Database::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $query->whereHas('company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        $query->with('engine');

        return $query->get();
    }

    public function update(DatabaseRequest $request): Collection
    {
        Database::where('id', $request->id)->update($request->only([
            'name',
            'engine_id',
        ]));

        return Database::where('id', $request->id)->get();
    }

    public function destroy(DatabaseRequest $request): Database|Collection
    {
        return DB::transaction(function () use ($request) {
            $database = Database::find($request->id);

            $database->tables()->delete();
            $database->delete();

            if (Database::where('id', $request->id)->exists()) {
                abort(500);
            }

            return $database;
        });
    }

    public function getLatest(Request|DatabaseRequest $request, ?int $id = null): array
    {
        if ($id) {
            $databaseId = Database::where('company_id', $request->cookie('currentCompany'))
                ->where('id', $id)
                ->first();

            if ($databaseId) {
                return $databaseId->toArray();
            }
        }

        $database = Database::where('company_id', $request->cookie('currentCompany'))
            ->latest()
            ->first();

        return $database?->toArray() ?? [ 'id' => null ];
    }
}
