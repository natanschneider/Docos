<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\TableRequest;
use App\Models\Table;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

final class TableRepository
{
    public function create(TableRequest $request): Table
    {
        return Table::create($request->only([
            'name',
            'database_id',
        ]));
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

    public function update(TableRequest $request): Collection
    {
        Table::where('id', $request->id)->update($request->only([
            'name',
            'database_id',
        ]));

        return Table::where('id', $request->id)->get();
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

    public function getWithColumns(Request $request): Collection
    {
        $query = Table::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('database_id')) {
            $query->where('database_id', $request->database_id);
        }

        if ($request->has('application_id')) {
            $query->whereHas('database.applications', function ($query) use ($request): void {
                $query->where('applications.id', $request->application_id);
            });
        }

        $query->whereHas('database.company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        $query->with('columns');

        return $query->get();
    }

    public function getLatest(Request|TableRequest $request, ?int $id = null): array
    {
        if ($id) {
            $tableId = Table::where('database_id', $request->cookie('currentDatabase'))
                ->whereHas('database.company', function ($query) use ($request): void {
                    $query->where('companies.id', $request->cookie('currentCompany'));
                })
                ->where('id', $id)
                ->first();

            if ($tableId) {
                return $tableId->toArray();
            }
        }

        $table = Table::where('database_id', $request->cookie('currentDatabase'))
            ->whereHas('database.company', function ($query) use ($request): void {
                $query->where('companies.id', $request->cookie('currentCompany'));
            })
            ->latest()
            ->first();

        return $table?->toArray() ?? ['id' => null];
    }
}
