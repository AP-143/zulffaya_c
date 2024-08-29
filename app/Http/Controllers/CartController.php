<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('cart.index');
    }
    public function addToCart(Request $request)
    {
        $product = $request->input('product');
        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product['id'])
            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
        } else {
            $cartItem = new Cart();
            $cartItem->user_id = $userId;
            $cartItem->product_id = $product['id'];
            $cartItem->quantity = 1;
            $cartItem->image = $product['image']; // Menyimpan URL gambar
        }

        $cartItem->save();

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json(['success' => true, 'cart_count' => $cartCount]);
    }

    public function getCart()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json(['cart' => $cartItems, 'cart_count' => $cartCount]);
    }

    public function updateCart(Request $request)
    {
        $userId = Auth::id();
        $itemId = $request->input('id');
        $action = $request->input('action');

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $itemId)
            ->first();

        if ($cartItem) {
            switch ($action) {
                case 'increase':
                    $cartItem->quantity += 1;
                    $cartItem->save();
                    break;
                case 'decrease':
                    if ($cartItem->quantity > 1) {
                        $cartItem->quantity -= 1;
                        $cartItem->save();
                    } else {
                        $cartItem->delete();
                    }
                    break;
                case 'remove':
                    $cartItem->delete();
                    break;
            }
        }

        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json(['success' => true, 'cart_count' => $cartCount]);
    }
}
