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
        $project = Project::findOrFail($request->project_id)->first();
        $company = Company::findOrFail($project->company_id);

        $companyBelongsToUser = $user->companies()
            ->where('companies.id', $company->first()->id)
            ->exists();

        if (! $companyBelongsToUser) {
            return false;
        }
        if (! $request->has('databases')) {
            return true;
        }

        return $company->databases()->whereIn('databases.id', $request->databases)->count() === count($request->databases);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application, ApplicationRequest $request): bool
    {
        $project = Project::findOrFail($request->has('project_id') ? $request->project_id : $application->project_id);
        $company = Company::findOrFail($project->company_id);

        $companyBelongsToUser = $user->companies()
            ->where('companies.id', $company->first()->id)
            ->exists();

        if (! $companyBelongsToUser) {
            return false;
        }

        if ($request->has('project_id') && $application->project_id !== (int) $request->project_id) {
            $currentCompany = Company::findOrFail($application->project->company_id);

            if ($company->first()->id !== $currentCompany->first()->id) {
                return false;
            }
        }

        if ((! $request->has('databases')) && (! $request->has('detach_databases'))) {
            return true;
        }

        $databases_check = $company->databases()->whereIn('databases.id', $request->databases)->count() === count($request->databases);

        if (! $databases_check) {
            return false;
        }

        $detach_check = 0;
        $application->screens()->each(function ($screen) use ($request, $detach_check): void {
            $detach_check += $screen->columns->databases()->whereIn('databases.id', $request->databases)->count();
        });

        $application->endpoints()->each(function ($endpoint) use ($request, $detach_check): void {
            $detach_check += $endpoint->columns->databases()->whereIn('databases.id', $request->databases)->count();
        });

        return $detach_check === 0;
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
