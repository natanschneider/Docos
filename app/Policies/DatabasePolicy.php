<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\DatabaseRequest;
use App\Models\Database;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatabasePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DatabaseRequest $request): Response
    {
        if (
            $request->has('company_id') &&
            $request->company_id !== 0 &&
            $request->company_id !== null &&
            $user->companies()->where('companies.id', $request->company_id)->doesntExist()
        ) {
            return Response::deny('Company provided does not belong to user or does not exist');
        }

        if (
            $request->has('id') &&
            $request->id !== 0 &&
            $request->id !== null &&
            $user->companies()->where('companies.id', Database::find($request->id)->company_id)->doesntExist()
        ) {
            return Response::deny('Database provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, DatabaseRequest $request): Response
    {
        return $user->companies()->where('companies.id', $request->company_id)->exists()
            ? Response::allow()
            : Response::deny('Company provided does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Database $database): Response
    {
        return $user->companies()->where('companies.id', $database->company->id)->doesntExist()
            ? Response::deny('Database provided does not belong to user or does not exist')
            : Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Database $database): Response
    {
        if ($user->companies()->where('companies.id', $database->company->id)->doesntExist()) {
            return Response::deny('Database provided does not belong to user or does not exist');
        }

        return $database->tables()->exists()
            ? Response::deny('Provided database contains tables')
            : Response::allow();
    }
}
