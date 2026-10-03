<?php

use App\Http\Controllers\RobotsController;
use Illuminate\Support\Facades\Route;

foreach (config('site.locales') as $locale) {
    Route::middleware('locale:'.$locale)->group(function () use ($locale): void {
        foreach (config('site.pages') as $page => $definition) {
            Route::view($definition['uri'][$locale], $definition['view'])->name($locale.'.'.$page);
        }
    });
}

Route::get('/robots.txt', RobotsController::class);
