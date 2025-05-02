<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\User\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // $users = User::paginate();
        $query = User::query();

        if ($request->has('role') && in_array($request->role, Role::allValues())) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate();
        return response()->success($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->errors('User not found');
        }
        return response()->success($user);
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
        return;
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

            if ($user->isOwner() && $user->startup) {
                $user->startup->delete();
            }
            $user->delete(); // Delete the user

            return response()->success('User deleted successfully');
        });
    }
}
