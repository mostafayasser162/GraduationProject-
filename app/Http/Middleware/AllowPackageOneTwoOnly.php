<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowPackageOneTwoOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $startup = auth()->user();

        if (!$startup) {
            return response()->errors('Unauthorized', [], 401);
        }

        // Allow only package_id 1 or 2
        if (!in_array($startup->package_id, [1, 2 ,4])) {
            return response()->errors('Access denied: Your package does not allow this action.', [], 403);
        }

        return $next($request);
    }
}
