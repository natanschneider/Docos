<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class ColumnRepository
{
    public function create(ColumnRequest $request): Column
    {
        return DB::transaction(function () use ($request) {
            $column = Column::create($request->only([
                'name',
                'doc_file',
                'table_id',
                'type_id',
            ]));

            if ($request->has('index') && $request->index === true) {
                $column->index()->create([
                    'column_id' => $column->id,
                ]);
            }

            if ($request->has('constraints')) {
                $column->constraints()->sync($request->constraints);
            }

            return $column->load([
                'index',
                'constraints',
            ]);
        });
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

        return $query->with([
            'index',
            'constraints',
        ])->get();
    }

    public function update(ColumnRequest $request): Column
    {
        if ($request->has('table_id')) {
            $company = Table::where('id', $request->table_id)->database->company;

            if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
                abort(403, 'Table does not belong to user or does not exist');
            }
        }

        return DB::transaction(function () use ($request): Column {
            $column = Column::findOrFail($request->id);
            $column->update($request->only([
                'name',
                'doc_file',
                'table_id',
                'type_id',
            ]));

            if ($request->has('index')) {
                $request->index === true
                    ? $column->index()->firstOrCreate(['column_id' => $column->id])
                    : optional($column->index)->delete();
            }

            return $column->load('index');
        });
    }

    public function destroy(ColumnRequest $request): Column
    {
        return DB::transaction(function () use ($request) {
            $column = Column::findOrFail($request->id);

            $column->index()->delete();
            $column->delete();

            return $column->load('index');
        });
    }
}
