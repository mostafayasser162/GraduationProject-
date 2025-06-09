<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\User\Role;
use App\Enums\User\Status;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->whereNot('role', Role::ADMIN())->with('orders.orderItems.product','orders' , 'orders.orderItems' , 'addresses');

        if ($request->has('role') && in_array($request->role, Role::allValues())) {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        return response()->paginate_resource(UserResource::collection($users));
    }

    public function show($id)
    {
        $user = User::find($id)->load('orders.orderItems' ,'orders' , 'orders.orderItems.product');

        if (!$user || $user->isAdmin()) {
            return response()->errors('User not found');
        }
        return response()->success(new UserResource($user));
    }

    public function checkDestroy($id)
    {
        $user = User::find($id);
        if ($user->isOwner() && $user->startup) {
            return response()->errors(
                'This user is an owner and has a startup do you sure of delete',
                ['key' => 'true']
            );
        }

        return response()->success('defult user');
    }
    public function destroy($id)
    {

        return DB::transaction(function () use ($id) {
            $user = User::find($id);

            if (!$user) {
                return response()->errors('User not found');
            }

            if ($user->isAdmin()) {
                return response()->errors('Cannot delete an admin');
            }

            if ($user->isOwner() || $user->startup) {
                $user->startup->delete();
            }
            $user->delete(); // Delete the user

            return response()->success('User deleted successfully');
        });
    }

    public function block($id)
    {
        $user = User::find($id);

        if (!$user || $user->isAdmin()) {
            return response()->errors('User not found');
        }

        if ($user->status == Status::APPROVED()) {
            $user->status = Status::BLOCKED();
        } elseif ($user->status == Status::BLOCKED()) {
            $user->status = Status::APPROVED();
        } else {
            return response()->errors('You can only toggle status between APPROVED and BLOCKED');
        }

        $user->save();

        return response()->success("User status changed to {$user->status}");
    }
}
