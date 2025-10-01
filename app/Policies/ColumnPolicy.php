<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ColumnRequest;
use App\Models\Column;
use App\Models\Table;
use App\Models\User;
use App\Validators\ColumnValidator;
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
            $validator = new ColumnValidator();
            $validator->createAttachmentFk($request);
            $validator->createAttachmentPk($request);
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
            $validator = new ColumnValidator();
            $validator->updateAttachmentFk($request, $column);
            $validator->updateAttachmentPk($request, $column);
        }

        if ($request->has('detach_constraints')) {
            $validator = new ColumnValidator();
            $validator->detachmentConstraints($request, $column);
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
