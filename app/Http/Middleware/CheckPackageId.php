<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPackageId
{
    public function handle(Request $request, Closure $next): Response
    {
        $startup = auth()->user();

        if (!$startup) {
            return response()->errors('No startup found.');
        }

        // Allow only package_id 3 or 4
        if (!in_array($startup->package_id, [3, 4])) {
            return response()->errors('Access denied. Your package does not allow this action.');
        }

        return $next($request);
    }
}
