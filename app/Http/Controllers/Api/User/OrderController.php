<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreOrderRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use App\Models\Product_size;
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
        $addressId = $validated['address_id'];
        if (!$addressId) {
            return response()->errors('Address is required');
        }

        $address = $user->addresses()->find($validated['address_id']);
        if (!$address) {
            return response()->errors('Invalid address');
        }
        // حساب السعر الإجمالي
        $total = $cart->sum(function ($product) {
            if ($product->pivot->product_size_id) {
                $variant = Product_size::find($product->pivot->product_size_id);
                return $variant ? $variant->price * $product->pivot->quantity : $product->price * $product->pivot->quantity;
            } else {
                return $product->price * $product->pivot->quantity;
            }
        });

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

            // ✅ إنشاء الأوردر مع ربط العنوان
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'address_id' => $validated['address_id'], // ✅ أضفنا العنوان
                'second_phone' => $validated['second_phone'],
            ]);

            foreach ($cart as $product) {
                if ($product->pivot->product_size_id) {
                    $variant = Product_size::find($product->pivot->product_size_id);

                    if ($variant && $variant->stock >= $product->pivot->quantity) {
                        $variant->decrement('stock', $product->pivot->quantity);
                        $product->decrement('stock', $product->pivot->quantity);
                        $price = $variant->price;
                    } else {
                        DB::rollBack();
                        return response()->errors('Not enough stock for product variant');
                    }
                } else {
                    if ($product->stock >= $product->pivot->quantity) {
                        $product->decrement('stock', $product->pivot->quantity);
                        $price = $product->price;
                    } else {
                        DB::rollBack();
                        return response()->errors('Not enough stock for product');
                    }
                }

                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_size_id' => $product->pivot->product_size_id,
                    'quantity' => $product->pivot->quantity,
                    'price' => $price,
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

    public function index()
    {
        $user = auth()->user();

        $orders = Order::with(['orderItems.product', 'orderItems', 'orderItems.productSize'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $orders = OrderResource::collection($orders);
        return response()->paginate_resource($orders);
    }



    public function show($orderId)
    {
        // Get the authenticated user
        $user = auth()->user();


        // Fetch the specific order with its items, product details, and product_size (if available)
        $order = Order::with(['orderItems.product', 'orderItems.productSize']) // Eager load order items, products, and product sizes
            ->where('user_id', $user->id)
            ->where('id', $orderId)
            ->first(); // We use first() because we expect only one order

        // Check if the order exists
        if (!$order) {
            return response()->errors('Order not found');
        }

        // Return the order details along with order items and product information
        return response()->success([
            'order' => new OrderResource($order) // Pass the order to your resource to format it
        ]);
    }
}
