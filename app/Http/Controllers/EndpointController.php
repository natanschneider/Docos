<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EndpointRequest;
use App\Models\Endpoint;
use App\Repositories\EndpointRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class EndpointController extends Controller
{
    public function store(EndpointRequest $request): Endpoint
    {
        Gate::authorize('create', [Endpoint::class, $request]);

        return (new EndpointRepository())->create($request);
    }

    public function get(EndpointRequest $request): Collection
    {
        Gate::authorize('view', [Endpoint::class, $request]);

        return (new EndpointRepository())->get($request);
    }

    public function update(EndpointRequest $request): Endpoint
    {
        Gate::authorize('update', [Endpoint::findOrFail($request->id), $request]);

        return (new EndpointRepository())->update($request);
    }

    public function destroy(EndpointRequest $request): Endpoint|Collection
    {
        Gate::authorize('delete', Endpoint::findOrFail($request->id));

        return (new EndpointRepository())->destroy($request);
    }
}
