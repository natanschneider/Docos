<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserCompanyController as UserCompany;
use App\Http\Requests\UserCompanyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserCompanyRequest $request): Response
    {
        $users = (new UserCompany())->get($request);

        return Inertia::render('resources/user-company/manipulate', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('resources/user-company/manipulate', [
            'users' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCompanyRequest $request): RedirectResponse
    {
        (new UserCompany())->store($request);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, UserCompanyRequest $request): JsonResponse
    {
        if ($request->user_email === $request->user()->email) {
            return response()->json([
                'message' => 'You cannot remove the current logged user from this company.',
            ], 403);
        }

        $request->merge(['id' => $id]);
        $response = (new UserCompany())->destroy($request);

        return response()->json($response);
    }
}
