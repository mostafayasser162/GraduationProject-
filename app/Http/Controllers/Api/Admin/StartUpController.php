<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Startup;
use Illuminate\Http\Request;
use App\Enums\StartUps\Status;
use App\Enums\User\Role;
use App\Http\Resources\StartupResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\StartupApprovedMail;
use App\Mail\StartupPaymentRequiredMail;
// use App\Mail\StartupProfileUpdatedMail;
use App\Mail\StartupRejectedMail;
use App\Mail\StartupProfileUpdatedMail;
use App\Mail\StartupProfileUpdatedMailRej;
use App\Mail\StartupTrialMail;
use Carbon\Carbon;
use App\Traits\PackageHelper;

class StartUpController extends Controller
{
    use PackageHelper;

    public function index(Request $request)
    {

        $query = Startup::with('user', 'products', 'category', 'package');

        if ($request->has('status')) {
            $status = $request->status;

            if (!in_array($status, Status::allValues())) {
                return response()->errors('Invalid status value');
            }

            $query->where('status', $status);
        }

        $startUps = $query->get();
        $startUps = StartupResource::collection($startUps);

        return response()->paginate_resource($startUps);
    }

    public function show($id)
    {
        $startUp = Startup::with('user', 'products', 'category', 'package')
            ->find($id);

        if (!$startUp) {
            return response()->errors('startUp not found');
        }

        return response()->success(new StartupResource($startUp));
    }


    public function destroy($id)
    {
        $startUp = Startup::find($id);
        if (!$startUp) {
            return response()->errors('startUp not found');
        }

        $startUp->delete();
        return response()->success('startUp deleted successfully');
    }

    public function block($id)
    {
        $startup = Startup::find($id);

        if (!$startup) {
            return response()->errors('Startup not found');
        }

        if ($startup->status == Status::APPROVED()) {
            $startup->status = Status::BLOCKED();
            $isBlocked = true;
        } elseif ($startup->status == Status::BLOCKED()) {
            $startup->status = Status::APPROVED();
            $isBlocked = false;
        } else {
            return response()->errors('You can only toggle status between APPROVED and BLOCKED');
        }

        $startup->save();

        return response()->success("Startup status changed to {$startup->status}", [
            'is_blocked' => $isBlocked,
        ]);
    }

    public function accept($id)
    {
        $startup = Startup::with('user')->find($id);

        if (!$startup || $startup->status != Status::PENDING()) {
            return response()->errors('Startup not found or its status not pending');
        }

        if (self::isBasicPackage($startup->package_id)) {
            $startup->status = Status::APPROVED();
            $startup->trial_ends_at = Carbon::now()->addDays(14);
            $startup->save();

            $startup->user->role = Role::OWNER();
            $startup->user->save();

            // Send trial start email with login page
            Mail::to($startup->email)->send(new StartupTrialMail($startup));
        } else {
            $startup->status = Status::HOLD();
            $startup->save();
            $startup->user->role = Role::OWNER();
            $startup->user->save();

            // Send payment required email with payment page
            Mail::to($startup->email)->send(new StartupPaymentRequiredMail($startup));
        }

        return response()->success("Startup has been approved");
    }

    public function reject($id)
    {
        $startup = Startup::with('user')->find($id);

        if (!$startup || $startup->status != Status::PENDING()) {
            return response()->errors('Startup not found or its status not pending');
        }

        $startup->status = Status::REJECTED();
        $startup->save();

        // Send rejection email
        Mail::to($startup->email)->send(new StartupRejectedMail($startup));

        $startup->delete();

        return response()->success("Startup has been rejected");
    }


    public function approvePendingUpdate($startupId)
    {
        $startup = Startup::findOrFail($startupId);

        if (!$startup->pending_update) {
            return response()->errors('No pending update found.');
        }

        // Decode JSON string to array
        $data = json_decode($startup->pending_update, true);

        $startup->update($data);
        $startup->pending_update = null;
        $startup->save();

        // Send email
        Mail::to($startup->email)->send(new StartupProfileUpdatedMail($startup));

        return response()->success('Startup profile updated successfully and email sent.');
    }
    // decline pending update
    public function declinePendingUpdate($startupId)
    {
        $startup = Startup::findOrFail($startupId);
        if (!$startup) {
            return response()->errors('Startup not found.');
        }

        if (!$startup->pending_update) {
            return response()->errors('No pending update found.');
        }

        // Clear the pending update
        $startup->pending_update = null;
        $startup->save();

        // Send email
        Mail::to($startup->email)->send(new StartupProfileUpdatedMailRej($startup));

        return response()->success('Pending update declined and email sent.');
    }
}
