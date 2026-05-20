<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\UserCompanyRequest;
use App\Models\Company;
use App\Models\User;

final class UserCompanyRepository
{
    static public function create(UserCompanyRequest $request)
    {
        Company::find($request->company_id)
            ->users()
            ->attach(User::where('email', $request->user_email)->pluck('id')->first());

        return Company::find($request->company_id)->users()->where('users.email', $request->user_email)->first();
    }

    static public function get(UserCompanyRequest $request)
    {
        return Company::find($request->company_id)->users()->get();
    }

    static public function delete(UserCompanyRequest $request)
    {
        return Company::find($request->company_id)
            ->users()
            ->detach(User::where('email', $request->user_email)->pluck('id')->first());
    }
}
