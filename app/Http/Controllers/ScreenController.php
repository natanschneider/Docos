<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;
use App\Repositories\ScreenRepository;

class ScreenController extends Controller
{
    public function store(ScreenRequest $request): Screen
    {
        return (new ScreenRepository())->create($request);
    }
}
