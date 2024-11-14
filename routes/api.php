<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

Route::group(['middleware' => ['web']], function () {
    Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
    Route::get('/get-qr-code-generated', [QrCodeController::class, 'getqrcodegenerated']);
    Route::post('/delete-generate-qr-code', [QrCodeController::class, 'deletegeneratedqr']);
    Route::get('/get-queue-status', [QrCodeController::class, 'queueProgress']);
});
