<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CompanyPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): Response
    {
        return $user->companies()->where('companies.id', $company->id)->exists()
            ? Response::allow()
            : Response::deny('Company provided does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): Response
    {
        return $company->users()->where('users.id', $user->id)->exists()
            ? Response::allow()
            : Response::deny('Company provided does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): Response
    {
        $companyBelongsToUser = $company->users()->where('users.id', $user->id)->exists();

        if (! $companyBelongsToUser) {
            return Response::deny('Company provided does not belong to user or does not exist');
        }

        return $company->projects()->doesntExist() && $company->databases()->doesntExist()
            ? Response::allow()
            : Response::deny('Company provided contains projects or databases');
    }
}
