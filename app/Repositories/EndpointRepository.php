<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\EndpointRequest;
use App\Models\Application;
use App\Models\Endpoint;
use Illuminate\Database\Eloquent\Collection;

final class EndpointRepository
{
    public function create(EndpointRequest $request): Endpoint
    {
        return Endpoint::create($request->all());
    }

    public function get(EndpointRequest $request): Collection
    {
        $query = Endpoint::query();

        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('application_id')) {
            $query->where('application_id', $request->application_id);
        }

        return $query->get();
    }

    public function update(EndpointRequest $request): Collection
    {
        if ($request->has('application_id')) {
            $company = Application::where('id', $request->application_id)->project->company;

            if ($request->user()->companies()->where('companies.id', $company->id)->doesntExist()) {
                abort(403, 'Application does not belong to user or does not exist');
            }
        }

        Endpoint::where('id', $request->id)->update($request->all());

        return Endpoint::where('id', $request->id)->get();
    }

    public function destroy(EndpointRequest $request): Endpoint|Collection
    {
        $endpoint = Endpoint::find($request->id);

        $endpoint->delete();

        return $endpoint;
    }
}
