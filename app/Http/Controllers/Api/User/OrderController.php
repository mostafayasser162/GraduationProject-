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

        // Calculate total price
        $total = $cart->sum(function($product) {
            // Check if the product has a size variant
            if ($product->pivot->product_size_id) {
                $variant = Product_size::find($product->pivot->product_size_id);
                return $variant ? $variant->price * $product->pivot->quantity : $product->price * $product->pivot->quantity;
            } else {
                // If no size variant, use the price from the product
                return $product->price * $product->pivot->quantity;
            }
        });

        DB::beginTransaction();
        try {
            // Handle payment method (Visa or Cash)
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

            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                // You can adjust the status based on payment method if needed
            ]);

            // Process each cart item
            foreach ($cart as $product) {
                // Check if the product has a variant (size/color)
                if ($product->pivot->product_size_id) {
                    // Get the specific product variant
                    $variant = Product_size::find($product->pivot->product_size_id);

                    if ($variant && $variant->stock >= $product->pivot->quantity) {
                        // Deduct stock from product_size table
                        $variant->decrement('stock', $product->pivot->quantity);

                        // Deduct stock from the main product table
                        $product->decrement('stock', $product->pivot->quantity);

                        // Create order item with the price from the product_size table
                        $price = $variant->price;
                    } else {
                        DB::rollBack();
                        return response()->errors('Not enough stock for product variant');
                    }
                } else {
                    // For products without variants
                    if ($product->stock >= $product->pivot->quantity) {
                        // Deduct stock from the main product table
                        $product->decrement('stock', $product->pivot->quantity);

                        // Create order item with the price from the product table
                        $price = $product->price;
                    } else {
                        DB::rollBack();
                        return response()->errors('Not enough stock for product');
                    }
                }

                // Create order item
                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_size_id' => $product->pivot->product_size_id, // Handle product variant size if exists
                    'quantity' => $product->pivot->quantity,
                    'price' => $price, // Use the correct price from product or product_size
                ]);
            }

            // Clear the cart after successful order placement
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

    $orders = Order::with(['orderItems.product', 'orderItems.productSize'])
                    ->where('user_id', $user->id)
                    ->latest()
                    ->get();

    return response()->success([
        'orders' => OrderResource::collection($orders)
    ]);
}



public function show($orderId)
{
    // Get the authenticated user
    $user = auth()->user() ;


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
