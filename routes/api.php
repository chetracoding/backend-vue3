<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ChangeProfileController;
use App\Http\Controllers\MoneyReportController;
use App\Http\Controllers\OnesignalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RecoverPasswordController;
use App\Http\Controllers\ProductCustomizeController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    // Users ------------------//
    // Register user by admin
    Route::post('/register', [UserController::class, 'register']);

    // Get auth user
    Route::get('/user', [UserController::class, 'getUser']);

    // Update profile  ---------------------//
    Route::put('/update_profile/{id}', [ChangeProfileController::class, 'updateProfile'] );

    // Chagne password
    Route::post('/changePassword', [ChangePasswordController::class, 'changePassword']);

    // Logout
    Route::post('/logout', [UserController::class, 'logout']);

    // Category ------------------//
    Route::resource('categories', CategoryController::class);

    // Prouduct ------------------//
    Route::resource('products', ProductController::class);
    // Search products
    Route::get('/products/search/{keyowrd}', [ProductController::class, 'search']);
    // Filter products
    Route::get('/products/filter/{category_id}', [ProductController::class, 'filter']);
    // Get popular products
    Route::get('/popular_products', [ProductController::class, 'popular']);

    // Prouduct customize ------------------//
    Route::delete('product_customizes/{id}', [ProductCustomizeController::class, 'destroy']);

    // Order ------------------//
    Route::resource('orders', OrderController::class);
    // Search order
    Route::get('/orders/search/{keyowrd}', [OrderController::class, 'search']);
    // Get orders completed or not
    Route::get('/orders/completed/{is_complete}', [OrderController::class, 'getByCompelted']);
    // Get orders paid or not
    Route::get('/orders/paid/{is_paid}', [OrderController::class, 'getByPaid']);

    // Staff ------------------//
    Route::resource('staff', StaffController::class);

    // Table ------------------//
    Route::resource('tables', TableController::class);

    // Role ------------------//
    Route::resource('roles', RoleController::class);

    // Onsignal ------------------//
    Route::post('onsignal', [OnesignalController::class, 'store']);

    // Product report ------------------//
    Route::get('product_report/{month}/{year}', [ProductReportController::class, 'productReport']);

    // Money report ------------------//
    Route::get('money_report/{year}', [MoneyReportController::class, 'moneyReport']);
});
// Login ------------------//
Route::post('/auth/login', [UserController::class, 'login']);

// Recover Password ------------------//
// Send link
Route::post('/recover_password', [RecoverPasswordController::class, 'sendResetPassword']);
// Check link
Route::post('/recover_password/check', [RecoverPasswordController::class, 'checkResetPassword']);
// Reset new password
Route::post('/recover_password/reset', [RecoverPasswordController::class, 'resetPassword']);