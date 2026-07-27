<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        if (!Auth::check()) {
            abort(403);
        }

        /** @var User $user */
        $user = Auth::user();

        if (!$user->hasAnyRole($roles)) {
            abort(403);
        }

        return $next($request);
    }
}