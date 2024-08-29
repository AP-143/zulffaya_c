<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'customer_phone' => $request->customer_phone,
            'total_price' => 0, // This will be updated later
            'status' => 'pending' // Default status
        ]);

        $totalPrice = 0;

        foreach ($request->cart as $cartItem) {
            $product = Product::find($cartItem['product_id']);
            $price = $product->price * $cartItem['quantity'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem['product_id'],
                'quantity' => $cartItem['quantity'],
                'price' => $price
            ]);

            $totalPrice += $price;
        }


        $order->update(['total_price' => $totalPrice]);

        // Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snap_token' => $snapToken]);
    }

    public function finish(Request $request)
    {
        $resultData = json_decode($request->input('result_data'), true);

        // Handle the result data as needed, e.g., update order status, etc.
        // Example:
        $order = Order::find($resultData['order_id']);
        $status = $resultData['transaction_status'];

        if ($status == 'settlement') {
            $status = 'berhasil';
        }

        $order->update(['status' => $status]);

        // Clear cart in session or database
        session()->forget('cart');

        return response()->json(['success' => true]);
    }
}
