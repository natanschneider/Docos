<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UserCompanyRequest;
use App\Policies\UserCompanyPolicy;
use App\Repositories\UserCompanyRepository;

class UserCompanyController extends Controller
{
    public function store(UserCompanyRequest $request)
    {
        UserCompanyPolicy::view($request->user(), $request);

        return UserCompanyRepository::create($request);
    }

    public function get(UserCompanyRequest $request)
    {
        UserCompanyPolicy::view($request->user(), $request);

        return UserCompanyRepository::get($request);
    }

    public function destroy(UserCompanyRequest $request)
    {
        UserCompanyPolicy::view($request->user(), $request);

        return UserCompanyRepository::delete($request);
    }
}
