<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\EndpointRequest;
use App\Models\Endpoint;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class EndpointRepository
{
    public function create(EndpointRequest $request): Endpoint
    {
        return DB::transaction(function () use ($request): Endpoint {
            $endpoint = Endpoint::create($request->only([
                'name',
                'application_id',
            ]));

            if ($request->has('columns')) {
                $endpoint->columns()->attach($request->columns);
            }

            return $endpoint->load('columns');
        });
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

        $query->whereHas('application.project.company', function ($query) use ($request): void {
            $query->whereIn('companies.id', $request->user()->companies()->pluck('companies.id')->toArray());
        });

        return $query->with('columns')->get();
    }

    public function update(EndpointRequest $request): Endpoint
    {
        return DB::transaction(function () use ($request): Endpoint {
            $endpoint = Endpoint::findOrFail($request->id);

            $endpoint->update($request->only([
                'name',
                'application_id',
            ]));

            if ($request->has('columns')) {
                $endpoint->columns()->syncWithoutDetaching($request->columns);
            }

            if ($request->has('detach_columns')) {
                $endpoint->columns()->detach($request->detach_columns);
            }

            return $endpoint->load('columns');
        });
    }

    public function destroy(EndpointRequest $request): Endpoint
    {
        return DB::transaction(function () use ($request) {
            $endpoint = Endpoint::findOrFail($request->id);

            $endpoint->columns()->detach();
            $endpoint->delete();

            return $endpoint->load('columns');
        });
    }
}
