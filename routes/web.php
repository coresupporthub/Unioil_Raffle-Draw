<?php

use Illuminate\Support\Facades\Route;

//ADMIN SIDE
Route::get('/', function () {
    return view('Admin.index');
})->name('index');

Route::get('/blank', function () {
    return view('Admin.blank');
})->name('blank');

Route::get('/QR/Generator', function () {
    return view('Admin.qrgenerator');
})->name('qrgenerator');




//CUSTOMER SIDE
Route::get('/Registration/Page', function () {
    return view('Customer.registration');
});

