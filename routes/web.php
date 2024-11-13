<?php

use Illuminate\Support\Facades\Route;

//ADMIN SIDE

//NAVIGATION
Route::get('/', function () {
    return view('Admin.index');
})->name('index');
Route::get('/blank', function () {
    return view('Admin.blank');
})->name('blank');
Route::get('/qr/generator', function () {
    return view('Admin.qrgenerator');
})->name('qrgenerator');

//AUTHENTICATION
Route::get('/admin/sign-in', function () {
    return view('Admin.signin');
})->name('adminsignin');
Route::get('/admin/verification-code', function () {
    return view('Admin.signin2');
})->name('adminsignin2');


//CUSTOMER SIDE
Route::get('/registration/page', function () {
    return view('Customer.registration');
});

