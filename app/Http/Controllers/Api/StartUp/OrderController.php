<?php

namespace App\Http\Controllers\Api\StartUp;

use App\Http\Controllers\Controller;
use App\Models\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // 1. Get all order items for this startup
    public function index()
    {
        $startupId = auth('startup')->user()->id;

        $orderItems = Order_item::with(['product', 'order', 'order.user.addresses'])
            ->whereHas('product', function ($query) use ($startupId) {
                $query->where('startup_id', $startupId);
            })
            ->orderByDesc('created_at')
            ->get();

        return response()->success('Order items retrieved successfully.', $orderItems);
    }

    // 2. View a specific order item & mark it as viewed
    public function show($id)
    {
        $orderItem = Order_item::with(['product', 'order.user' , 'order.user.addresses'])->find($id);

        if (!$orderItem || !$orderItem->product) {
            return response()->errors('Product not found', 404);
        }

        $startupId = auth('startup')->id();

        if ($orderItem->product->startup_id !== $startupId) {
            return response()->errors('Unauthorized access', 403);
        }

        if (!$orderItem->viewed) {
            $orderItem->viewed = 1;
            $orderItem->save();
        }

        return response()->success('Order item retrieved successfully.', $orderItem);
    }

    // 3. Count number of unviewed order items for this startup
    public function countNewOrders()
    {
        $startupId = auth('startup')->user()->id;

        $newOrdersCount = Order_item::where('viewed', 0)
            ->whereHas('product', function ($query) use ($startupId) {
                $query->where('startup_id', $startupId);
            })
            ->count();

        return response()->success('New orders count retrieved successfully.', ['new_orders_count' => $newOrdersCount]);
    }
}
