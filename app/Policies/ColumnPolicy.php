<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Models\Table;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ColumnPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ColumnRequest $request): Response
    {
        if (
            $request->has('table_id') &&
            $user->companies()
                ->where(
                    'company_id',
                    Table::findOrFail($request->table_id)->database->company_id
                )
                ->doesntExist()
        ) {
            return Response::deny('Table provided does not belong to user or does not exist');
        }

        if (
            $request->has('id') &&
            $user->companies()
                ->where(
                    'company_id',
                    Column::findOrFail($request->id)->table->database->company_id
                )
                ->doesntExist()
        ) {
            return Response::deny('Column provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ColumnRequest $request): Response
    {
        if (
            $request->has('table_id') &&
            $user->companies()
                ->where(
                    'company_id',
                    Table::findOrFail($request->table_id)->database->company_id
                )
                ->doesntExist()
        ) {
            return Response::deny('Table provided does not belong to user or does not exist');
        }

        if ($request->has('related_columns')) {
            if (isset($request->related_columns['pk'])) {
                $pk_counter = count($request->related_columns['pk']);

                $qr_pk_counter = Column::query();

                $qr_pk_counter->whereIn('id', $request->related_columns['pk']);
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not exist');
                }

                $qr_pk_counter->whereHas('table', function ($query) use ($request): void {
                    $query->whereHas('database', function ($subQuery) use ($request): void {
                        $subQuery->where('id', Table::find($request->table_id)->database_id);
                    });
                });
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not belong to same database');
                }

                $qr_pk_counter->whereHas('constraints', function ($query): void {
                    $query->where('constraints.id', 1);
                });
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not have a primary key constraint');
                }
            }

            if (isset($request->related_columns['fk'])) {
                $fk_counter = count($request->related_columns['fk']);

                $qr_fk_counter = Column::query();

                $qr_fk_counter->whereIn('id', $request->related_columns['fk']);
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not exist');
                }

                $qr_fk_counter->whereHas('table', function ($query) use ($request): void {
                    $query->whereHas('database', function ($subQuery) use ($request): void {
                        $subQuery->where('id', Table::find($request->table_id)->database_id);
                    });
                });
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not belong to same database');
                }

                $qr_fk_counter->whereHas('constraints', function ($query): void {
                    $query->whereIn('constraints.id', [2, 9, 10]);
                });
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not have a foreing key constraint');
                }
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Column $column, ColumnRequest $request): Response
    {
        if ($user->companies()->where('companies.id', $column->table->database->company_id)->doesntExist()) {
            return Response::deny('Column does not belong to user or does not exist');
        }

        if (
            $request->has('table_id') &&
            Table::findOrFail($request->table_id)->database->company_id !== $column->table->database->company_id
        ) {
            return Response::deny('Table provided does not belong to same company as column');
        }

        if ($request->has('related_columns')) {
            if (isset($request->related_columns['pk'])) {
                $pk_counter = count($request->related_columns['pk']);

                $qr_pk_counter = Column::query();

                $qr_pk_counter->whereIn('id', $request->related_columns['pk']);
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not exist');
                }

                $qr_pk_counter->whereHas('table', function ($query) use ($column): void {
                    $query->whereHas('database', function ($subQuery) use ($column): void {
                        $subQuery->where('id', $column->table->database_id);
                    });
                });
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not belong to same database');
                }

                $qr_pk_counter->whereHas('constraints', function ($query): void {
                    $query->where('constraints.id', 1);
                });
                if ($qr_pk_counter->count() !== $pk_counter) {
                    return Response::deny('Some of the provided primary keys do not have a primary key constraint');
                }
            }

            if (isset($request->related_columns['fk'])) {
                $fk_counter = count($request->related_columns['fk']);

                $qr_fk_counter = Column::query();

                $qr_fk_counter->whereIn('id', $request->related_columns['fk']);
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not exist');
                }

                $qr_fk_counter->whereHas('table', function ($query) use ($column): void {
                    $query->whereHas('database', function ($subQuery) use ($column): void {
                        $subQuery->where('id', $column->table->database_id);
                    });
                });
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not belong to same database');
                }

                $qr_fk_counter->whereHas('constraints', function ($query): void {
                    $query->whereIn('constraints.id', [2, 9, 10]);
                });
                if ($qr_fk_counter->count() !== $fk_counter) {
                    return Response::deny('Some of the provided foreing keys do not have a foreing key constraint');
                }
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Column $column): Response
    {
        if ($user->companies()->where('companies.id', $column->table->database->company_id)->doesntExist()) {
            return Response::deny('Column does not belong to user or does not exist');
        }

        if ($column->screens()->exists() || $column->endpoints()->exists()) {
            Response::deny('Column provided contains endpoints or screens');
        }

        if ($column->relatedFks()->exists() || $column->relatedPks()->exists()) {
            Response::deny('Column provided contains related primary keys or foreign keys');
        }

        return Response::allow();
    }
}
