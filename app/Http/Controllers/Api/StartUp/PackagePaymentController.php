<?php 
namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Mail\StartupActiveMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PackagePaymentController extends Controller
{

    public function getPackageDetails(Request $request)
    {
        // Retrieve the authenticated user based on the token
        $user = Auth::user();

        // Ensure the user exists
        if (!$user) {
            return response()->errors('Unauthorized or invalid user.', 401);
        }

        // Check if the user has a related startup with status HOLD
        $startup = $user->startup; // Assuming the user has a related startup model

        if (!$startup || !in_array($startup->status, ['HOLD'])) {
            return response()->errors('Unauthorized or invalid user.', 401);
        }

        // Retrieve the package details
        $package = $startup->package; // Assuming the startup has a related package model

        if (!$package) {
            return response()->errors('Package not found.', 404);
        }

        // Calculate the date after 30 days
        $repaymentDate = now()->addDays(30)->toDateString();

        return response()->success( [
            'package_id' => $package->id,
            'package_name' => $package->name,
            'price' => $package->price,
            'repayment_date' => $repaymentDate, // Display the repayment date
        ]);
    }
    // public function pay(Request $request)
    // {
    //     $startup = Auth::user(); // authenticated startup

    //     if (!$startup || !$startup->isStartup()) {
    //         return response()->errors('Unauthorized');
    //     }

    //     // Only allow payment if status is HOLD (payment pending)
    //     if ($startup->status !== 'HOLD') {
    //         return response()->errors('This action is only allowed when payment is pending.');
    //     }

    //     // Mark startup as APPROVED and clear trial if any
    //     $startup->update([
    //         'status' => 'APPROVED',
    //         'trial_ends_at' => null,
    //         'package_ends_at'  => now()->addDays(30),
    //     ]);

    //     // Send confirmation email
    //     Mail::to($startup->user->email)->send(new StartupActiveMail($startup));
    //     return response()->success('Payment successful. Your account is now active.', $startup);
    // }

    public function pay(Request $request)
    {
        // Retrieve the authenticated user based on the token
        $user = Auth::user();

        // Ensure the user exists
        if (!$user) {
            return response()->errors('Unauthorized or invalid user.', 401);
        }

        // Check if the user has a related startup with status HOLD
        $startup = $user->startup; // Assuming the user has a related startup model

        if (!$startup || $startup->status !== 'HOLD') {
            return response()->errors('This action is only allowed when payment is pending.', 403);
        }

        // Mark startup as APPROVED and clear trial if any
        $startup->update([
            'status' => 'APPROVED',
            'trial_ends_at' => null,
            'package_ends_at' => now()->addDays(30),
        ]);

        // Send confirmation email
        Mail::to($user->email)->send(new StartupActiveMail($startup));

        return response()->success('Payment successful. Your account is now active.', [
            'startup_id' => $startup->id,
            'status' => $startup->status,
            'package_ends_at' => $startup->package_ends_at,
        ]);
    }

}
