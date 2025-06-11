<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\PackageHelper;

class CheckPackageId
{
    use PackageHelper;

    public function handle(Request $request, Closure $next): Response
    {
        $startup = auth()->user();

        if (!$startup) {
            return response()->errors('No startup found.');
        }

        // Allow only Pro Marketing packages (3 or 4)
        if (!self::isProSupplychainPackage($startup->package_id)) {
            return response()->errors('Access denied. Your package does not allow this action.');
        }

        return $next($request);
    }
}
