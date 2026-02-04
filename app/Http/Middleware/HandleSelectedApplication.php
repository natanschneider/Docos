<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\ApplicationController;

class HandleSelectedApplication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request): ?int
    {
        if (! $request->user()) {
            return null;
        }

        $id = $request->hasCookie('currentApplication') ? (int) $request->cookie('currentApplication') : (new ApplicationController())->getLatest($request)['id'];
        $request->cookies->set('currentApplication', $id);

        return $id;
    }
}
