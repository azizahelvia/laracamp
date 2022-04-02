<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->name('/');

// Socialite routes
Route::get('/login-google', [UserController::class, 'loginGoogle'])->name('user-login-google');
Route::get('auth/google/callback', [UserController::class, 'handleProviderCallback'])->name('user-google-callback');

Route::middleware(['auth'])->group(function() {
    // User dashboard
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/checkout/invoice', [CheckoutController::class. 'invoice'])->name('user.checkout.invoice');

    // Checkout routes
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/{camp:slug}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{camp}', [CheckoutController::class, 'store'])->name('checkout.store');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
