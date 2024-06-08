<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaticController;
use App\Http\Controllers\CheckoutController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [StaticController::class, 'index'])->name('home.index');
Route::get('/about', [StaticController::class, 'about'])->name('home.about');
Route::get('/contact', [StaticController::class, 'contact'])->name('home.contact');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout');
Route::post('/retrieved_payment', [CheckoutController::class, 'verifyPayment'])->name('retrieved_payment');
Route::get('/payment_status', [CheckoutController::class, 'getPaymentStatus'])->name('payment_status');
Route::post('/update_payment_status', [CheckoutController::class, 'updatePaymentStatus'])->name('update_payment_status');



Route::resource('shop', ShopController::class);

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/mhan', function () {
//     $filter = request('style');
//     return "<h1>{$filter}</h1>";
// });

// Route::get('/about', function () {
//     return view('about');
// });


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';