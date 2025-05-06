<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_item;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{

    public function placeOrder(StoreOrderRequest $request)
    {

        $validated = $request->validated();
        $user = auth()->user();
        $cart = $user->cart;

        if ($cart->isEmpty()) {
            return response()->errors('Cart is empty');
        }

        $total = $cart->sum(fn($product) => $product->price * $product->pivot->quantity);

        DB::beginTransaction();
        try {
            if ($validated['payment_method'] == 'visa') {

                $cardNumber = $validated['card_number'];
                $expiryDate = $validated['expiry_date'];
                $cvv = $validated['cvv'];

                if (!$cardNumber || !$expiryDate || !$cvv) {
                    DB::rollBack();
                    return response()->errors('Invalid card data');
                }

                sleep(2);
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                // 'status' => $validated['payment_method'] === 'cash' ? 'pending' : 'processing',
            ]);

            foreach ($cart as $product) {
                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_size_id' => $product->pivot->product_size_id, //3atlan hena ya hana
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->price,
                ]);
            }

            $user->cart()->detach();

            DB::commit();

            return response()->success([
                'message' => $validated['payment_method'] === 'visa' ? 'Payment processed successfully' : 'Order placed with cash',
                'order' => new OrderResource($order),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->errors('Order failed: ' . $e->getMessage());
        }
    }
}
