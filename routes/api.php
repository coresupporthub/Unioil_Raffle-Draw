<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;

Route::group(['middleware' => ['web']], function () {
    Route::post('/generate-qr-code', [QrCodeController::class, 'generate']);
});
