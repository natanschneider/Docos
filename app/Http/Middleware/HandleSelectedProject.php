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

        $id = ($request->hasCookie('currentProject') && $request->cookie('currentProject')) ? (int) $request->cookie('currentProject') : (new ProjectController())->getLatest($request)['id'];

        if ($id === 0 or $id === '0') {
            $id = null;
        }

        $request->cookies->set('currentProject', $id);

        return $id;
    }
}
