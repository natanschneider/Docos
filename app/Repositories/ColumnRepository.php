<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

final class ColumnRepository
{
    public function create(ColumnRequest $request): Column
    {
        return Column::create($request->all());
    }

    public function get(ColumnRequest $request): Collection
    {
        $query = Column::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('table_id')) {
            $query->where('table_id', $request->table_id);
        }

        return $query->get();
    }

    public function update(ColumnRequest $request): Collection
    {
        if ($request->has('table_id')) {
            $company = Table::where('id', $request->table_id)->database->company;

            if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
                abort(403, 'Table does not belong to user or does not exist');
            }
        }

        Column::where('id', $request->id)->update($request->all());

        return Column::where('id', $request->id)->get();
    }

    public function destroy(ColumnRequest $request): Column|Collection
    {
        $column = Column::find($request->id);

        $column->delete();

        return $column;
    }
}
