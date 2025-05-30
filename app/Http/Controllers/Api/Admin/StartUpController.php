<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Startup;
use Illuminate\Http\Request;
use App\Enums\StartUps\Status;
use App\Http\Resources\StartupResource;
use Illuminate\Support\Facades\Mail;
use App\Mail\StartupApprovedMail;
use App\Mail\StartupRejectedMail;



class StartUpController extends Controller
{
    public function index(Request $request)
    {

        $query = Startup::with('user' , 'products');

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
        $startUp = Startup::with('user' , 'products')->find($id);

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
        } elseif ($startup->status == Status::BLOCKED()) {
            $startup->status = Status::APPROVED();
        } else {
            return response()->errors('You can only toggle status between APPROVED and BLOCKED');
        }

        $startup->save();

        return response()->success("Startup status changed to {$startup->status}");
    }

    // public function accept($id)
    // {
    //     $startup = Startup::find($id);

    //     if (!$startup || $startup->status != Status::PENDING()) {
    //         return response()->errors('Startup not found or its status not pending');
    //     }

    //     $startup->status = Status::APPROVED();
    //     $startup->save();

    //     return response()->success("Startup has been approved");
    // }


    public function accept($id)
    {
        $startup = Startup::with('user')->find($id);
    
        if (!$startup || $startup->status != Status::PENDING()) {
            return response()->errors('Startup not found or its status not pending');
        }
    
        $startup->status = Status::APPROVED();
        $startup->save();
    
        // Send approval email
        Mail::to($startup->email)->send(new StartupApprovedMail($startup));
    
        return response()->success("Startup has been approved");
    }
    

    // public function reject($id)
    // {
    //     $startup = Startup::find($id);

    //     if (!$startup || $startup->status != Status::PENDING()) {
    //         return response()->errors('Startup not found or its status not pending');
    //     }

    //     $startup->status = Status::REJECTED();
    //     $startup->save();
    //     $startup->delete();


    //     return response()->success("Startup has been rejected");
    // }


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

}
