<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EndpointRequest;
use App\Repositories\EndpointRepository;
use Illuminate\Database\Eloquent\Collection;

class EndpointController extends Controller
{
    public function store(EndpointRequest $request): Collection
    {
        return (new EndpointRepository())->create($request);
    }

    public function get(EndpointRequest $request): Collection
    {
        if ($request->has('id')) {
            (new EndpointRequest())->ensureEndpointBelongsToUser($request);
        }

        return (new EndpointRepository())->get($request);
    }

    public function update(EndpointRequest $request): Collection
    {
        (new EndpointRequest())->ensureEndpointBelongsToUser($request);

        return (new EndpointRepository())->update($request);
    }

    public function destroy(EndpointRequest $request): Collection
    {
        (new EndpointRequest())->ensureEndpointBelongsToUser($request);

        return (new EndpointRepository())->destroy($request);
    }
}
