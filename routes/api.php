<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\AuthenticationController;

Route::group(['middleware' => ['web']], function () {
    Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
    Route::get('/get-qr-code-generated', [QrCodeController::class, 'getqrcodegenerated']);
    Route::post('/delete-generate-qr-code', [QrCodeController::class, 'deletegeneratedqr']);
    Route::get('/get-queue-status', [QrCodeController::class, 'queueProgress']);
    Route::post('/export-qr', [QrCodeController::class, 'exportQR']);
    Route::post('/admin/auth', [AuthenticationController::class, 'signin']);
});
