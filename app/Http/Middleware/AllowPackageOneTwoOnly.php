<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\PackageHelper;

class AllowPackageOneTwoOnly
{
    use PackageHelper;

    public function handle(Request $request, Closure $next): Response
    {
        $startup = auth()->user();

        if (!$startup) {
            return response()->errors('Unauthorized', [], 401);
        }

        // Allow only Basic packages (1 or 2)
        if (!self::isBasicPackage($startup->package_id)) {
            return response()->errors('Access denied: Your package does not allow this action.', [], 403);
        }

        return $next($request);
    }
}
