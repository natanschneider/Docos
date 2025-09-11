<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\EndpointRequest;
use App\Models\Endpoint;

final class EndpointRepository
{
    public function create(EndpointRequest $request): Endpoint
    {
        return Endpoint::create($request->all());
    }
}
