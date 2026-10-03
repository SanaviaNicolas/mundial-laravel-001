<?php

use App\Http\Controllers\RobotsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home');
Route::view('/menu', 'menu');
Route::view('/la-nostra-storia', 'storia');
Route::view('/contatti', 'contatti');
Route::get('/robots.txt', RobotsController::class);
