<?php

namespace App\Http\Middleware;

use App\Enums\StartUps\Status;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

class CheckStartupStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $startup = auth()->user();
        // Allow access to payment route without blocking
        if ($request->is('api/startup/pay-package')) {
            return $next($request);
        }

        if (!$startup) {
            return response()->errors('no startup found');
        }

        if ($startup->status == Status::BLOCKED()) {
            return response()->errors('Your account has been blocked. Please contact the administration.');
        }

        if ($startup->status == Status::PENDING()) {
            return response()->errors('Your account is pending approval by the administration.');
        }

        if ($startup->status == Status::REJECTED()) {
            return response()->errors('Your account status is invalid.');
        }
        if ($startup->status == Status::HOLD()) {
            // Prevent access until payment is done
            return response()->errors('Your payment is pending. Please complete the payment to access the website.', [
                'redirect_to' => url('/payment-page') // you can customize the redirect URL
            ]);
        }

        return $next($request);
    }
}
