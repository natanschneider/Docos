<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Repositories\ApplicationRepository;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request): Application
    {
        return (new ApplicationRepository())->create($request);
    }
}
