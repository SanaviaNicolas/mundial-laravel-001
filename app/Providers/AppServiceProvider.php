<?php

namespace App\Providers;

use App\Menu\DatabaseMenu;
use App\Menu\MenuSource;
use App\Menu\StaticMenu;
use App\Support\Closures;
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
        $this->app->bind(MenuSource::class, fn () => config('menu.source') === 'database' ? new DatabaseMenu : new StaticMenu);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.app', 'home', 'menu', 'storia', 'contatti', 'legale'], fn ($view) => $view->with([
            'tel' => 'tel:'.preg_replace('/\D/', '', config('site.phone')),
            'mapUrl' => config('site.map_url'),
            'pageUrl' => Pages::url(...),
            'chiusura' => Closures::notice(),
        ]));
        View::composer(['home', 'menu'], fn ($view) => $view->with('menu', app(MenuSource::class)->sections()));
    }
}
