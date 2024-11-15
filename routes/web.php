<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Models\QrCode;
use Illuminate\Support\Facades\Storage;

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/', function () {
        return view('Admin.index');
    })->name('index');
    Route::get('/blank', function () {
        return view('Admin.blank');
    })->name('blank');
    Route::get('/qr/generator', function () {
        return view('Admin.qrgenerator');
    })->name('qrgenerator');
    Route::get('/raffle/draw', function () {
        return view('Admin.raffledraw');
    })->name('raffledraw');
    Route::get('/retail/outlets', function () {
        return view('Admin.retailoutlets');
    })->name('retailoutlets');
    Route::get('/raffle/entries', function () {
        return view('Admin.raffleentries');
    })->name('raffleentries');

    //SETTINGS
    Route::get('/account/settings', function () {
        return view('Admin.accountsettings');
    })->name('accountsettings');

    Route::get('/qr-code/{fileName}', function ($fileName) {
        $path = "qr-codes/{$fileName}";

        return response()->file(storage_path("app/{$path}"));

    })->name('qr_images');
});


//AUTHENTICATION
Route::get('/admin/sign-in', function () {
    return view('Admin.signin');
})->name('adminsignin');
Route::get('/admin/verification-code', function () {
    return view('Admin.signin2');
})->name('adminsignin2');


//CUSTOMER SIDE
Route::get('/registration/page/{code}/{uuid}', function ($code, $uuid) {

    $checkCode = QrCode::where('qr_id', $uuid)->where('code', $code)->first();
    if(!$checkCode){
       abort(402);
    }

    $checkUsed = QrCode::where('qr_id', $uuid)->where('code', $code)->where('status', 'used')->first();

    if($checkUsed){
        abort(402);
    }

    return view('Customer.registration', ['code'=> $code, 'uuid'=> $uuid]);
})->name('customer_registrations');
