<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\EndpointRequest;
use App\Models\Endpoint;
use App\Repositories\EndpointRepository;

class EndpointController extends Controller
{
    public function store(EndpointRequest $request): Endpoint
    {
        return (new EndpointRepository())->create($request);
    }
}
