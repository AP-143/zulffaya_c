<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->where('status', '!=', 'shipped')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function accept(Order $order)
    {
        $order->update(['status' => 'shipped']);
        return redirect()->route('admin.orders.index')->with('success', 'Order accepted and moved to shipment list successfully.');
    }

    public function reject(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order rejected and deleted successfully.');
    }

    public function shipments()
    {
        $orders = Order::with('items.product')->where('status', 'shipped')->get();
        return view('admin.orders.shipments', compact('orders'));
    }
}
