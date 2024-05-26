<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MerchandizerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product-detail/{id}', [ProductDetailController::class, 'show'])->name('product.detail');
Route::post('/add-to-cart/{id}', [ProductDetailController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [ProductDetailController::class, 'cartIndex'])->name('cart.index');

// Registration Routes
Route::get('/merchandizer-register', [RegisterController::class, 'showRegistrationForm'])->name('merchandizer-register');
Route::post('/merchandizer-register', [RegisterController::class, 'create'])->name('merchandizer-registration');

// Login Route to ensure it goes through AuthenticatedSessionController
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// Authenticated Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Apply role.redirect middleware to dashboard route only
    Route::middleware(['role.redirect'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Product Category Routes
    Route::resource('product_category', ProductCategoryController::class);
    Route::get('/product_category/create', [ProductCategoryController::class, 'create'])->name('product_category.create');

    // User Routes
    Route::resource('user', App\Http\Controllers\UserController::class);

    // Admin Routes
    Route::resource('admin/merchandizers', MerchandizerController::class);
});
