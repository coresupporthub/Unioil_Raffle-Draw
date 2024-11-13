<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Admin.index');
});

Route::get('/blank', function () {
    return view('Admin.blank');
});

