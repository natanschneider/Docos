<?php

declare(strict_types=1);

namespace App\Validators;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Models\Table;
use Illuminate\Auth\Access\Response;

class ColumnValidator
{
    public function getCurrentConstraints(ColumnRequest $request, Column $column): array
    {
        $constraints = $column->constraints()->get(['constraints.id'])->toArray();
        if ($request->has('detach_constraints')) {
            $constraints = $column->constraints()
                ->whereNotIn('constraints.id', $request->detach_constraints)
                ->get(['constraints.id'])->toArray();
        }
        $constraints = is_array($constraints) ? array_column($constraints, 'id') : [];

        if ($request->has('constraints')) {
            array_push(
                $constraints,
                ...$request->constraints
            );
        }

        return $constraints;
    }

    public function createAttachmentPk(ColumnRequest $request): Response|bool
    {
        if (isset($request->related_columns['pk'])) {
            if (! ($request->has('constraints') && (array_intersect([2, 9, 10], $request->constraints) !== []))) {
                return Response::deny('Relating primary keys requires a foreign key constraint');
            }

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

        return true;
    }

    public function createAttachmentFk(ColumnRequest $request): Response|bool
    {
        if (isset($request->related_columns['fk'])) {
            if (! ($request->has('constraints') && (array_intersect([1], $request->constraints) !== []))) {
                return Response::deny('Relating primary keys requires a foreign key constraint');
            }

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

        return true;
    }

    public function updateAttachmentPk(ColumnRequest $request, Column $column): Response|bool
    {
        if (isset($request->related_columns['pk'])) {
            $constraints = $this->getCurrentConstraints($request, $column);

            if (! ($request->has('constraints') && (array_intersect([2, 9, 10], $constraints) !== []))) {
                return Response::deny('Relating primary keys requires a foreign key constraint');
            }
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

        return true;
    }

    public function updateAttachmentFk(ColumnRequest $request, Column $column): Response|bool
    {
        if (isset($request->related_columns['fk'])) {
            $constraints = $this->getCurrentConstraints($request, $column);

            if (! ($request->has('constraints') && (array_intersect([1], $constraints) !== []))) {
                return Response::deny('Relating primary keys requires a foreign key constraint');
            }
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

        return true;
    }

    public function detachmentConstraints(ColumnRequest $request, Column $column): Response|bool
    {
        if ($request->has('detach_constraints')) {
            $constraints = $column->constraints()
                ->whereIn('constraints.id', $request->detach_constraints)
                ->whereIn('constraints.id', [1, 2, 9, 10])
                ->pluck('constraints.id')
                ->toArray();

            if (is_array($constraints) && count($constraints) > 0) {
                $column_qry = Column::query();
                if (
                    in_array(1, $constraints) &&
                    $column_qry->whereHas('relatedFks', function ($query) use ($column): void {
                        $query->where('columns.id', $column->id);
                    })->exists()
                ) {
                    return Response::deny('Column has related foreign keys');
                }

                if (
                    (array_intersect([2, 9, 10], $constraints) !== []) &&
                    $column_qry->whereHas('relatedPks', function ($query) use ($column): void {
                        $query->where('columns.id', $column->id);
                    })->exists()
                ) {
                    return Response::deny('Column has related primary keys');
                }
            }
        }

        return true;
    }
}
