<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Factory;
use Illuminate\Http\Request;
use App\Enums\Factory\Status;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\StoreFactoryRequest;
use App\Http\Requests\Admin\UpdateFactoryRequest;

class FactoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Factory::query();

        if ($request->filled('status') && in_array($request->status, Status::allValues())) {
            $query->where('status', $request->status);
        }

        $factories = $query->paginate();

        return response()->success($factories);
    }

    public function show(Factory $factory)
    {
        return response()->success($factory);
    }

    public function store(StoreFactoryRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $factory = Factory::create($data);

        return response()->success($factory, 'Factory created successfully');
    }

    public function destroy(Factory $factory)
    {
        $factory->delete();

        return response()->success('Factory deleted successfully');
    }


    public function block($id)
    {
        $startup = Factory::find($id);

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

}
