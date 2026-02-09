<?php

use App\Http\Controllers\UserApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('usuarios',UserApiController::class);
