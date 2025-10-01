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
                $column->constraints()->attach($request->constraints);
            }

            if ($request->has('related_columns')) {
                if (isset($request->related_columns['pk'])) {
                    $column->relatedPks()->attach($request->related_columns['pk']);
                }

                if (isset($request->related_columns['fk'])) {
                    $column->relatedFks()->attach($request->related_columns['fk']);
                }
            }

            return $column->load([
                'index',
                'constraints',
                'relatedPks',
                'relatedFks',
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


        $query->whereHas('table.database.company', function ($query) use ($request) {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->with([
            'index',
            'constraints',
            'relatedPks',
            'relatedFks',
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

            if ($request->has('constraints')) {
                $column->constraints()->syncWithoutDetaching($request->constraints);
            }

            if ($request->has('detach_constraints')) {
                $column->constraints()->detach($request->detach_constraints);
            }

            if ($request->has('related_columns')) {
                if (isset($request->related_columns['pk'])) {
                    $column->relatedPks()->syncWithoutDetaching($request->related_columns['pk']);
                }

                if (isset($request->related_columns['fk'])) {
                    $column->relatedFks()->syncWithoutDetaching($request->related_columns['fk']);
                }
            }

            if ($request->has('detach_related_columns')) {
                if (isset($request->detach_related_columns['pk'])) {
                    $column->relatedPks()->detach($request->detach_related_columns['pk']);
                }

                if (isset($request->detach_related_columns['fk'])) {
                    $column->relatedFks()->detach($request->detach_related_columns['fk']);
                }
            }

            return $column->load([
                'index',
                'constraints',
                'relatedPks',
                'relatedFks',
            ]);
        });
    }

    public function destroy(ColumnRequest $request): Column
    {
        return DB::transaction(function () use ($request) {
            $column = Column::findOrFail($request->id);

            $column->index()->delete();
            $column->constraints()->detach();
            $column->relatedPks()->detach();
            $column->relatedFks()->detach();
            $column->delete();

            return $column->load([
                'index',
                'constraints',
                'relatedPks',
                'relatedFks',
            ]);
        });
    }
}
