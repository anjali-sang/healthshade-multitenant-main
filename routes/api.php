<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/purchase_list', [PurchaseController::class, 'getPurchaseList']);
    Route::post('/purchase_order', [PurchaseController::class, 'getPurchaseOrder']);
    Route::post('/update_purchase', [PurchaseController::class, 'updatePurchaseOrder']);
    Route::post('/location/all', [LocationController::class, 'getLocations']);
    Route::post('/inventory',[InventoryController::class,'inventoryStatus']);
    Route::post('/pick/code',[InventoryController::class,'pickByCode']);
    Route::post('/pick/update',[InventoryController::class,'pickUpdate']);
    Route::post('/products/{id}/upload-image', [InventoryController::class, 'uploadImage']);

});
