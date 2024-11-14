<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
//ADMIN SIDE

//NAVIGATION

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
    Route::get('/coupon/management', function () {
        return view('Admin.managecoupon');
    })->name('managecoupon');
    Route::get('/retail/outlets', function () {
        return view('Admin.retailoutlets');
    })->name('retailoutlets');
    Route::get('/customer/registrations', function () {
        return view('Admin.customers');
    })->name('customers');

    //SETTINGS
    Route::get('/account/settings', function () {
        return view('Admin.accountsettings');
    })->name('accountsettings');

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
    return view('Customer.registration', ['code'=> $code, 'uuid'=> $uuid]);
})->name('customer_registrations');
