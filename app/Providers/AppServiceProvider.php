<?php

namespace App\Providers;

use App\Support\Menu;
use App\Support\Pages;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'home', 'menu', 'storia', 'contatti', 'legale'], fn ($view) => $view->with([
            'tel' => 'tel:'.preg_replace('/\D/', '', config('site.phone')),
            'mapUrl' => 'https://www.google.com/maps/search/?api=1&query='.urlencode(config('site.address')),
            'pageUrl' => Pages::url(...),
            'menu' => Menu::categories(),
        ]));
    }
}
