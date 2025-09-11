<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request): Application
    {
        return (new ApplicationRepository())->create($request);
    }
}
