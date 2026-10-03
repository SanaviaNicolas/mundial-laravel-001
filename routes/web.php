<?php

use App\Http\Controllers\RobotsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::view('/menu', 'menu');
Route::get('/robots.txt', RobotsController::class);
