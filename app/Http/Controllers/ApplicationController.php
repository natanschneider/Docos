<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Database\Eloquent\Collection;

class ApplicationController extends Controller
{
    public function store(ApplicationRequest $request): Application
    {
        return (new ApplicationRepository())->create($request);
    }

    public function get(ApplicationRequest $request): Collection
    {
        if ($request->has('id')) {
            (new ApplicationRequest())->ensureApplicationBelongsToUser($request);
        }

        return (new ApplicationRepository())->get($request);
    }

    public function update(ApplicationRequest $request): Collection
    {
        (new ApplicationRequest())->ensureApplicationBelongsToUser($request);

        return (new ApplicationRepository())->update($request);
    }

    public function destroy(ApplicationRequest $request): Application|Collection
    {
        $formRequest = new ApplicationRequest();
        $formRequest->ensureApplicationBelongsToUser($request);
        $formRequest->ensureApplicationIsEmpty($request);

        return (new ApplicationRepository())->destroy($request);
    }
}
