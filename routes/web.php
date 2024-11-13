<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Admin.index');
});

Route::get('/blank', function () {
    return view('Admin.blank');
});

Route::get('/Registration/Page', function () {
    return view('Customer.registration');
});
