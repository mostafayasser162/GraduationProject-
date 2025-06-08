<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTrialPeriod
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // public function handle($request, Closure $next)
    // {
    //     $startup = auth()->user();

    //         // Allow payment route without blocking
    // if ($request->is('api/startup/pay-package')) {
    //     return $next($request);
    // }


    //     if ($startup && $startup->isStartup() && $startup->trial_ends_at) {
    //         if (now()->gt($startup->trial_ends_at)) {
    //             $startup->status = 'HOLD';
    //             $startup->save();
    //             return response()->errors('The trial period has ended. Please pay');
    //         }
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next)
{
    $startup = auth()->user();

    // Allow access to trial payment route for expired trials
    if ($request->is('api/startup/pay-package')) {
        return $next($request);
    }

    if ($startup && $startup->isStartup() && $startup->trial_ends_at) {
        if (now()->gt($startup->trial_ends_at)) {
            $startup->status = 'HOLD';
            $startup->save();

            // Use your existing response()->errors() and include redirect info
            return response()->errors('The trial period has ended. Please pay', [
                'redirect_to' => url('/pay-package?package_id=1')
            ]);
        }
    }

    return $next($request);
}

}
