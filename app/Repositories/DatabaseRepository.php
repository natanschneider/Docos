<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class DatabaseRepository
{
    public function create(DatabaseRequest $request): Database
    {
        return Database::create($request->all());
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

        $query->whereHas('company', function ($query) use ($request) {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->get();
    }

    public function update(DatabaseRequest $request): Collection
    {
        if ($request->has('company_id') && $request->user()->companies()->where('companies.id', $request->company_id)->doesntExist()) {
            abort(403, 'Provided company does not belong to user');
        }

        Database::where('id', $request->id)->update($request->all());

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
}
