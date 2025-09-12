<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TableRequest;
use App\Models\Database;
use App\Models\Table;
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

        return $query->get();
    }

    public function update(TableRequest $request)
    {
        if ($request->has('database_id')) {
            $database = Database::where('id', $request->database_id)->first();

            if ($request->user()->companies()->where('companies.id', $database->company_id)->doesntExist()) {
                abort(403, 'Database does not belong to user or does not exist');
            }
        }

        Table::where('id', $request->id)->update($request->all());

        return Table::where('id', $request->id)->first();
    }
}
