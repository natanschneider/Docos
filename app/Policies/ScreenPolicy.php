<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\ScreenRequest;
use App\Models\Application;
use App\Models\Column;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ScreenPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ScreenRequest $request): Response
    {
        if ($request->has('application_id') && $request->application_id != 0) {
            $company = Application::find($request?->application_id)?->project?->company;

            if ($user->companies()->where('companies.id', $company?->id)->doesntExist()) {
                return Response::deny('Application provided does not belong to user or does not exist');
            }
        }

        if (
            $request->has('id') &&
            $user->companies()->where(
                'companies.id',
                Screen::findOrFail($request->id)?->application?->project?->company_id
            )->doesntExist()
        ) {
            return Response::deny('Screen provided does not belong to user or does not exist');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ScreenRequest $request): Response
    {
        $company = Application::find($request->application_id)->project->company;

        if ($user->companies()->where('companies.id', $company->id)->doesntExist()) {
            return Response::deny('Application provided does not belong to user or does not exist');
        }

        if ($request->has('columns')) {
            $cl = Column::whereIn('id', $request->columns)
                ->whereHas('table.database.company', function ($query) use ($company) {
                    $query->where('id', $company->id);
                })
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
    public function update(User $user, Screen $screen, ScreenRequest $request): Response
    {
        $application_id = $request->has('application_id') ? $request->application_id : $screen->application_id;
        $company = Application::find($application_id)->project->company;

        if (
            $user->companies()->where(
                'companies.id',
                $screen->application->project->company_id
            )
                ->doesntExist()
        ) {
            return Response::deny('Screen does not belong to user or does not exist');
        }

        if ($request->has('application_id') && $request->application_id !== $screen->application_id) {
            if ($user->companies()->where('companies.id', $company->id)->doesntExist()) {
                return Response::deny('Application provided does not belong to user or does not exist');
            }
            if ($screen->application->project->company_id !== $company->id) {
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

            if ($request->has('application_id') && $request->application_id !== $screen->application_id) {
                $currentColumns = $screen->columns()->pluck('id')->toArray();

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
    public function delete(User $user, Screen $screen): Response
    {
        return $user->companies()->where('companies.id', $screen->application->project->company_id)->exists()
            ? Response::allow()
            : Response::deny('Screen does not belong to user or does not exist');
    }

    public function handleFiles(User $user, Screen $screen): Response
    {
        return $user->companies()
            ->where('companies.id', $screen->application->project->company_id)
            ->doesntExist()
                ? Response::deny('Screen does not belong to user or does not exist')
                : Response::allow();
    }
}
