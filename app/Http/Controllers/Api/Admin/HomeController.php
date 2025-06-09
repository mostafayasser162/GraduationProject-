<?php

namespace App\Http\Controllers\Api\Admin;

use App\Enums\Factory\Status as FactoryStatus;
use App\Enums\StartUps\Status as StartupStatus;
use App\Enums\User\Status as UserStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function adminOverview()
    {
        $totalUsers = \App\Models\User::count();
        $blockedUsers = \App\Models\User::where('status', UserStatus::BLOCKED())->count();

        $activeStartups = \App\Models\Startup::where('status', StartupStatus::APPROVED())->count();
        $pendingStartups = \App\Models\Startup::where('status', StartupStatus::PENDING())->count();
        $blockedStartups = \App\Models\Startup::where('status', StartupStatus::BLOCKED())->count();

        $totalCategories = \App\Models\Category::count();
        $totalSubCategories = \App\Models\Sub_category::count();

        $totalFactories = \App\Models\Factory::count();
        $activeFactories = \App\Models\Factory::where('status', FactoryStatus::APPROVED())->count();

        $blockedFactories = \App\Models\Factory::where('status', FactoryStatus::BLOCKED())->count();

        return response()->json([
            'users' => [
                'total' => $totalUsers,
                'blocked' => $blockedUsers,
            ],
            'startups' => [
                'active' => $activeStartups,
                'pending_approvals' => $pendingStartups,
                'blocked' => $blockedStartups,
            ],
            'categories' => [
                'total' => $totalCategories,
                'sub_categories' => $totalSubCategories,
            ],
            'factories' => [
                'total' => $totalFactories,
                'active_Factories' => $activeFactories,
                'blocked' => $blockedFactories,
            ]
        ]);
    }
}
