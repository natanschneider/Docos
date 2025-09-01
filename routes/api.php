<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthenticatedSessionController::class, 'createToken']);

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
