<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\RetailStoreController;

Route::group(['middleware' => ['web']], function () {

    Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
    Route::get('/get-qr-code-generated', [QrCodeController::class, 'getqrcodegenerated']);
    Route::post('/delete-generate-qr-code', [QrCodeController::class, 'deletegeneratedqr']);

    // Retail Store Controller
    Route::post('/add-retail-store', [RetailStoreController::class, 'addcluster']);
    Route::get('/get-cluster', [RetailStoreController::class, 'getcluster']);
    Route::post('/cluster-status', [RetailStoreController::class, 'clusterstatus']);
    Route::post('/add-region', [RetailStoreController::class, 'addregion']);
    Route::post('/get-region-by-cluster', [RetailStoreController::class, 'getregionbycluster']);
    Route::post('/add-city', [RetailStoreController::class, 'addcity']);
});
