<?php

use Illuminate\Support\Facades\Route;
// \Laravel\Telescope\Telescope::routes();

Route::get('/', function () {
    return view('welcome');
});

