<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RejectStartupMail;
use App\Models\Startup;
use Illuminate\Http\Request;
use App\Enums\StartUps\Status;
use Illuminate\Support\Facades\Mail;

class StartUpController extends Controller
{
    public function index(Request $request)
    {

        $query = Startup::with('user');

        if ($request->has('status')) {
            $status = $request->status;

            if (!in_array($status, Status::allValues())) {
                return response()->errors('Invalid status value');
            }

            $query->where('status', $status);
        }

        $startUps = $query->paginate();

        return response()->success($startUps);
    }

    public function show($id)
    {
        $startUp = Startup::with('user')->find($id);

        if (!$startUp) {
            return response()->errors('startUp not found');
        }
        return response()->success($startUp);
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
        } elseif ($startup->status == Status::BLOCKED()) {
            $startup->status = Status::APPROVED();
        } else {
            return response()->errors('You can only toggle status between APPROVED and BLOCKED');
        }

        $startup->save();

        return response()->success("Startup status changed to {$startup->status}");
    }

    public function accept($id)
    {
        $startup = Startup::find($id);

        if (!$startup || $startup->status != Status::PENDING()) {
            return response()->errors('Startup not found or its status not pending');
        }

        $startup->status = Status::APPROVED();
        $startup->save();

        return response()->success("Startup has been approved");
    }
    public function reject($id)
    {
        $startup = Startup::find($id);

        // if (!$startup || $startup->status != Status::PENDING()) {
        //     return response()->errors('Startup not found or its status not pending');
        // }

        $startup->status = Status::REJECTED();
        $startup->save();

        if ($startup->user && $startup->user->email) {
            Mail::to($startup->user->email)->send(new RejectStartupMail($startup));
        }

        // $startup->delete();

        return response()->success("Startup has been rejected and email sent");
    }
}
