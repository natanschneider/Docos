<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\TableRequest;
use App\Models\Database;
use App\Models\Table;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TablePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TableRequest $request): Response
    {
        if (
            $request->has('database_id') &&
            $request->database_id !== 0 &&
            $request->database_id !== null &&
            $user->companies()
                ->where(
                    'company_id',
                    Database::find($request->database_id)?->company_id
                )
                ->doesntExist()
        ) {
            return Response::deny('Database provided does not belong to user or does not exist');
        }

        if (
            $request->has('id') &&
            $request->id !== 0 &&
            $request->id !== null &&
            $user->companies()
                ->where(
                    'company_id',
                    Table::find($request->id)?->database?->company_id
                )
                ->doesntExist()
        ) {
            return Response::deny('Table provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, TableRequest $request): Response
    {
        return $user->companies()->where('companies.id', Database::findOrFail($request->database_id)->company_id)->exists()
            ? Response::allow()
            : Response::deny('Database provided does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Table $table, TableRequest $request): Response
    {
        if ($user->companies()->where('companies.id', $table->database->company_id)->doesntExist()) {
            return Response::deny('Table does not belong to user or does not exist');
        }

        if (
            $request->has('database_id') &&
            $table->database_id !== (int) $request->database_id &&
            $table->database()->company_id !== Database::findOrFail($request->database_id)->company_id
        ) {
            return Response::deny('Database provided does not belong to the same company as the table');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Table $table): Response
    {
        if ($user->companies()->where('companies.id', $table->database->company_id)->doesntExist()) {
            return Response::deny('Table does not belong to user or does not exist');
        }

        return $table->columns()->exists()
            ? Response::deny('Table provided contains columns')
            : Response::allow();
    }

    public function handleFiles(User $user, Table $table): Response
    {
        return $user->companies()
            ->where('companies.id', $table->database->company_id)
            ->doesntExist()
                ? Response::deny('Table does not belong to user or does not exist')
                : Response::allow();
    }
}
