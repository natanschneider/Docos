<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectRequest $request): Response
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
            $user->companies()->where(
                'companies.id',
                Project::find($request->id)?->company_id
            )->doesntExist()
        ) {
            return Response::deny('Project provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ProjectRequest $request): Response
    {
        return $user->companies()->where('companies.id', $request->company_id)->exists()
            ? Response::allow()
            : Response::deny('Company provided does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): Response
    {
        return $user->companies()->where('companies.id', $project->company_id)->exists()
            ? Response::allow()
            : Response::deny('Project does not belong to user or does not exist');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): Response
    {
        if ($user->companies()->where('companies.id', $project->company_id)->doesntExist()) {
            return Response::deny('Project does not belong to user or does not exist');
        }

        return $project->applications()->doesntExist()
            ? Response::allow()
            : Response::deny('Project provided contains applications');
    }
}
