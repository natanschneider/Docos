<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleSelectedProject
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request): ?int
    {
        if (! $request->user()) {
            return null;
        }

        $id = $request->hasCookie('currentProject') ? (int) $request->cookie('currentProject') : null;
        $id = (new ProjectController())->getLatest($request, $id)['id'];

        $request->cookies->set('currentProject', $id);

        return $id;
    }
}
