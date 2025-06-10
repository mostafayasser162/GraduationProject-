<?php 
namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Mail\StartupActiveMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PackagePaymentController extends Controller
{
    public function pay(Request $request)
    {
        $startup = Auth::user(); // authenticated startup

        if (!$startup || !$startup->isStartup()) {
            return response()->errors('Unauthorized');
        }

        // Only allow payment if status is HOLD (payment pending)
        if ($startup->status !== 'HOLD') {
            return response()->errors('This action is only allowed when payment is pending.');
        }

        // Mark startup as APPROVED and clear trial if any
        $startup->update([
            'status' => 'APPROVED',
            'trial_ends_at' => null,
            'package_ends_at'  => now()->addDays(30),
        ]);

        // Send confirmation email
        Mail::to($startup->user->email)->send(new StartupActiveMail($startup));
        return response()->success('Payment successful. Your account is now active.', $startup);
    }
}
