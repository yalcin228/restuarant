<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StaffController;

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


Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('register', [AuthController::class, 'register']);


Route::middleware(['auth:sanctum', 'role:super-admin'])->group(function () {
    Route::apiResource('restaurants', RestaurantController::class);
    Route::post('create-admin', [RestaurantController::class, 'createAdmin']);
});

Route::middleware(['auth:sanctum', 'check.end_date'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        //4. Sifarişlər:
        Route::get('online-orders', [OrderController::class, 'getOnlineOrders']);
        Route::post('online-order', [OrderController::class, 'createOnlineOrder']);


        //9. Tanımlar - Stok Qrup Tanımları:
        Route::apiResource('menus', MenuController::class);
        Route::apiResource('menu-items', MenuItemController::class);
        Route::post('menu-items/{id}/update', [MenuItemController::class, 'updateItem']);
        Route::post('menu-items/{id}/update-stock', [MenuItemController::class, 'updateStock']);
        Route::post('menu-items/{id}/update-price', [MenuItemController::class, 'updatePrice']);
        Route::post('menu-items/{id}/update-type', [MenuItemController::class, 'updateType']);
        Route::get('menu-items/{id}/stock-histories', [MenuItemController::class, 'getStockHistories']);
        Route::post('menu-items/{id}/stock-proccess', [MenuItemController::class, 'stockProccess']);

        //10. Tanımlar - Personel Tanımları:
        Route::apiResource('staffs', StaffController::class);
    });
   
    

    //Masa tanimlari
    Route::middleware(['role:admin,staff','permission:manage-tables'])->group(function () {
        Route::get('categories', [TableController::class, 'getCategories']);
        Route::get('tables', [TableController::class, 'getTables']);
        Route::post('table', [TableController::class, 'createTable']);
        Route::put('table/{id}', [TableController::class, 'updateTable']);
        Route::delete('table/{id}', [TableController::class, 'deleteTable']);
    });

   


    // Route::apiResource('tables', TableController::class);
    // Route::apiResource('categories', CategoryController::class);
    // Route::apiResource('menu-items', MenuItemController::class);
    // Route::apiResource('expenses', ExpenseController::class);
});

Route::middleware(['auth:sanctum', 'role:staff'])->group(function () {
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('transactions', TransactionController::class);
});