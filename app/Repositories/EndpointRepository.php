<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\EndpointRequest;
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
}
