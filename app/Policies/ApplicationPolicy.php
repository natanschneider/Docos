<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Application $application): bool
    {
        return $user->companies()
            ->where('companies.id', $application->project->company_id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ApplicationRequest $request): bool
    {
        $company = Company::findOrFail(Project::findOrFail($request->project_id)->company_id);

        return $user->companies()
            ->where('companies.id', $company->first()->id)
            ->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application): bool
    {
        return $user->companies()
            ->where('companies.id', $application->project->company_id)
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): bool
    {
        if ($application->endpoints()->exists() || $application->screens()->exists() || $application->databases()->exists()) {
            return false;
        }

        return $user->companies()
            ->where('companies.id', $application->project->company_id)
            ->exists();
    }
}
