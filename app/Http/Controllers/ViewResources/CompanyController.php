<?php

declare(strict_types=1);

namespace App\Http\Controllers\ViewResources;

use App\Http\Controllers\CompanyController as Company;
use App\Http\Controllers\Controller;
use App\Http\Middleware\HandleSelectedCompany;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CompanyRequest $request): Response
    {
        $companies = (new Company)->get($request);

        return Inertia::render('resources/company/list', [
            'companies' => $companies,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('resources/company/manipulate', [
            'company' => null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request): RedirectResponse
    {
        (new Company)->store($request);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, CompanyRequest $request): Response
    {
        $request->merge(['id' => $id]);
        $company = (new Company)->get($request);

        return Inertia::render('resources/company/manipulate', [
            'company' => $company,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id, CompanyRequest $request): Response
    {
        $request->merge(['id' => $id]);
        $company = (new Company)->get($request);

        return Inertia::render('resources/company/manipulate', [
            'company' => Inertia::always($company[0]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request): RedirectResponse
    {
        (new Company)->update($request);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id, CompanyRequest $request): JsonResponse
    {
        $request->merge(['id' => $id]);
        $response = (new Company)->destroy($request);

        new HandleSelectedCompany()->handle($request);

        return response()->json($response);
    }
}
