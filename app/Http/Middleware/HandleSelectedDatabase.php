<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\DatabaseController;
use Closure;
use Illuminate\Http\Request;

class HandleSelectedDatabase
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request): ?int
    {
        if (! $request->user()) {
            return null;
        }

        $id = ($request->hasCookie('currentDatabase') && $request->cookie('currentDatabase')) ? (int) $request->cookie('currentDatabase') : (new DatabaseController())->getLatest($request)['id'];

        if ($id === 0 or $id === '0') {
            $id = null;
        }

        $request->cookies->set('currentDatabase', $id);

        return $id;
    }
}
