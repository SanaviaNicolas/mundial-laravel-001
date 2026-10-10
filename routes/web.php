<?php

use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

foreach (config('app.locales') as $locale) {
    Route::middleware('locale:'.$locale)->group(function () use ($locale): void {
        foreach (config('site.pages') as $page => $definition) {
            Route::view($definition['uri'][$locale], $definition['view'])->name($locale.'.'.$page);
        }
    });
}

Route::get('/robots.txt', RobotsController::class);
Route::get('/sitemap.xml', SitemapController::class);
