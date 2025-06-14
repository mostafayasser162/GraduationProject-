<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckStartupPayment
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
    
            // ✅ Only apply to 'owner' users
            if ($user->role === 'owner') {
                $startup = $user->startup;
    
                // ❌ No startup relation
                if (!$startup) {
                    return errors('You must have a startup associated with your account.', 'no_startup', 403);
                }
    
                // ✅ If startup is on HOLD, allow only these 2 routes
                if ($startup->status === 'HOLD') {
                    if (
                        $request->is('api/user/pay-package') ||
                        $request->is('api/user/package-id')
                    ) {
                        return $next($request); // allow access
                    }
    
                    return errors('Your account is on hold. Please complete your payment to access other services.', 'account_on_hold', 403);
                }
    
                // ✅ Package must exist
                if (!$startup->package_id) {
                    return errors('Please select a package to continue.', 'no_package', 403);
                }
    
                // ✅ Free trial logic
                if (in_array($startup->package_id, [1, 5])) {
                    if (!$startup->trial_ends_at) {
                        return errors('Please complete registration to start free trial.', 'trial_not_started', 403);
                    }
    
                    if (Carbon::parse($startup->trial_ends_at)->isPast() && !$startup->package_ends_at) {
                        return errors('Free trial ended. Please pay to continue.', 'trial_ended', 403);
                    }
                } else {
                    // ✅ Paid packages (2,3,4,6,7,8)
                    if (!$startup->package_ends_at) {
                        return errors('Please make a payment to access services.', 'payment_required', 403);
                    }
                }
    
                // ✅ Expired package
                if ($startup->package_ends_at && Carbon::parse($startup->package_ends_at)->isPast()) {
                    return errors('Your package expired. Please renew.', 'package_expired', 403);
                }
            }
        }
    
        return $next($request);
    }
    
}
