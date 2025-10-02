<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ApplicationRequest $request): Response
    {
        if (
            $request->has('id') &&
            $user->companies()
                ->where('companies.id', Application::findOrFail($request->id)->project->company_id)
                ->doesntExist()
        ) {
            return Response::deny('Application provided does not belong to user or does not exist');
        }

        if (
            $request->has('project_id') &&
            $user->companies()->where('companies.id', Project::findOrFail($request->project_id)->company_id)->doesntExist()
        ) {
            return Response::deny('Project provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ApplicationRequest $request): Response
    {
        $project = Project::findOrFail($request->project_id);
        $company = Company::findOrFail($project->company_id);

        $companyBelongsToUser = $user->companies()
            ->where('companies.id', $company->id)
            ->get();

        if (! $companyBelongsToUser) {
            return Response::deny('Company provided does not belong to user or does not exist');
        }
        if (! $request->has('databases')) {
            return Response::allow();
        }

        return $company->databases()->whereIn('databases.id', $request->databases)->count() === count($request->databases)
            ? Response::allow()
            : Response::deny('Databases provided do not belong to company provided');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application, ApplicationRequest $request): Response
    {
        $project = Project::findOrFail($request->has('project_id') ? $request->project_id : $application->project_id);
        $company = Company::findOrFail($project->company_id);

        $companyBelongsToUser = $user->companies()
            ->where('companies.id', $company->id)
            ->exists();

        if (! $companyBelongsToUser) {
            return Response::deny('Company provided does not belong to user or does not exist');
        }

        if ($request->has('project_id') && $application->project_id !== (int) $request->project_id) {
            $currentCompany = Company::findOrFail($application->project->company_id);

            if ($company->id !== $currentCompany->id) {
                return Response::deny('Company provided does not belong to user or does not exist');
            }
        }

        if ((! $request->has('databases')) && (! $request->has('detach_databases'))) {
            return Response::allow();
        }

        $databases_check = $company->databases()->whereIn('databases.id', $request->databases)->count() === count($request->databases);

        if (! $databases_check) {
            return Response::deny('Databases provided do not belong to company provided');
        }

        $detach_check = 0;
        $application->screens()->each(function ($screen) use ($request, $detach_check): void {
            $detach_check += $screen->columns->databases()->whereIn('databases.id', $request->databases)->count();
        });

        $application->endpoints()->each(function ($endpoint) use ($request, $detach_check): void {
            $detach_check += $endpoint->columns->databases()->whereIn('databases.id', $request->databases)->count();
        });

        return $detach_check === 0 ? Response::allow() : Response::deny('You are detaching databases that are in use');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): Response
    {
        if ($application->endpoints()->exists() || $application->screens()->exists()) {
            return Response::deny('Application provided contains endpoints or screens');
        }

        return $user->companies()
            ->where('companies.id', $application->project->company_id)
            ->exists()
                ? Response::allow()
                : Response::deny('Application provided does not belong to user or does not exist');
    }
}
