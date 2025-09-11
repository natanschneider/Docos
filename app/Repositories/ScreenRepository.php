<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\ScreenRequest;
use App\Models\Screen;

final class ScreenRepository
{
    public function create(ScreenRequest $request): Screen
    {
        return Screen::create($request->all());
    }
}
