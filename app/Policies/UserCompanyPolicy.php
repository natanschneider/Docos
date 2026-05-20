<?php

declare(strict_types=1);

namespace App\Policies;

use App\Http\Requests\UserCompanyRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserCompanyPolicy
{
    static public function view(User $user, UserCompanyRequest $request)
    {
        if (! $user->companies()->where('companies.id', $request->company_id)->exists()) {
            return Response::deny('User does not have access to this company');
        }

        if ($request->has('user_email')) {
            if (User::where('email', $request->user_email)->doesntExist()) {
                return Response::deny('User does not exist');
            }
        }

        return Response::allow();
    }
}
