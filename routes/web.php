<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\RegisterController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('create-admin', [RegisterController::class, 'createAdmin']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');


Route::get('/product', function () {
    return view('product');
})->name('product');

Route::get('/product', function () {
    $products = \App\Models\Product::all();
    return view('product', compact('products'));
})->name('product');
Route::get('/product', [ProductController::class, 'index'])->name('product');

//admin//
Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('admin');

Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/admin/products/store', [ProductController::class, 'store'])->name('admin.products.store');
Route::get('/admin/products/edit/{id}', [ProductController::class, 'edit'])->name('admin.products.edit');
Route::post('/admin/products/update/{id}', [ProductController::class, 'update'])->name('admin.products.update');
Route::delete('/admin/products/delete/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

Route::resource('products', ProductController::class);

//cart//
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');

//ceckout//
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
Route::post('/checkout/finish', [CheckoutController::class, 'finish'])->name('checkout.finish');
Route::post('/cart/clear', function () {
    session()->forget('cart');
    return response()->json(['success' => true]);
})->name('cart.clear');

//admin/orders//

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
    Route::get('/shipments', [OrderController::class, 'shipments'])->name('orders.shipments');
});
Route::get('/order-details', [OrderController::class, 'orderDetails'])->name('order.details');
