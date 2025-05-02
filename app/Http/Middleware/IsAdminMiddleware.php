<?php

namespace App\Http\Middleware;

use App\Enums\User\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {

        $admin = Role::ADMIN();

        if (! in_array(Auth::user()->role, [$admin])) {
            return response()->errors(('unauthorized action'));
        }

        return $next($request);
    }
}
