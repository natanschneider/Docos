<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\EndpointRequest;
use App\Models\Application;
use App\Models\Column;
use App\Models\Endpoint;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EndpointPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EndpointRequest $request): Response
    {
        if ($request->has('application_id')) {
            $company = Application::find($request->application_id)->project->company;

            if ($user->companies()->where('companies.id', $company->id)->doesntExist()) {
                return Response::deny('Application provided does not belong to user or does not exist');
            }
        }

        if (
            $request->has('id') &&
            $user->companies()->where(
                'companies.id',
                Endpoint::findOrFail($request->id)->application->project->company_id
            )->doesntExist()
        ) {
            return Response::deny('Endpoint provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, EndpointRequest $request): Response
    {
        $company = Application::find($request->application_id)->project->company;

        if ($user->companies()->where('companies.id', $company->id)->doesntExist()) {
            return Response::deny('Application provided does not belong to user or does not exist');
        }

        if ($request->has('columns')) {
            $cl = Column::whereIn('id', $request->columns)
                ->has('table.database.company', $company->id)
                ->count();

            return $cl === count($request->columns)
                ? Response::allow()
                : Response::deny('Columns provided do not belong to application provided');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Endpoint $endpoint, EndpointRequest $request): Response
    {
        $application_id = $request->has('application_id') ? $request->application_id : $endpoint->application_id;
        $company = Application::find($application_id)->project->company;

        if (
            $user->companies()->where(
                'companies.id',
                $endpoint->application->project->company_id
            )
                ->doesntExist()
        ) {
            return Response::deny('Endpoint does not belong to user or does not exist');
        }

        if ($request->has('application_id') && $request->application_id !== $endpoint->application_id) {
            if ($user->companies()->where('companies.id', $company->id)->doesntExist()) {
                return Response::deny('Application provided does not belong to user or does not exist');
            }
            if ($endpoint->application->project->company_id !== $company->id) {
                return Response::deny('Application provided does not belong to same company');
            }
        }

        if ($request->has('columns')) {
            $cl = Column::whereIn('id', $request->columns)
                ->has('table.database.company', $company->id)
                ->count();

            if ($cl !== count($request->columns)) {
                return Response::deny('Columns provided do not belong to same company');
            }

            if ($request->has('application_id') && $request->application_id !== $endpoint->application_id) {
                $currentColumns = $endpoint->columns()->pluck('id')->toArray();

                if ($company->databases->columns()->whereIn('columns.id', $currentColumns)->count() !== count($currentColumns)) {
                    return Response::deny('Columns do not belong to application provided');
                }
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Endpoint $endpoint): Response
    {
        return $user->companies()->where('companies.id', $endpoint->application->project->company_id)->exists()
            ? Response::allow()
            : Response::deny('Endpoint does not belong to user or does not exist');
    }

    public function handleFiles(User $user, Endpoint $endpoint): Response
    {
        return $user->companies()
            ->where('companies.id', $endpoint->application->project->company_id)
            ->doesntExist()
                ? Response::deny('Endpoint does not belong to user or does not exist')
                : Response::allow();
    }
}
