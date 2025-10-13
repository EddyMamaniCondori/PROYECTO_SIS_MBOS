<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- importante
use Illuminate\Support\Facades\DB;   // <-- importante

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    

    public function boot(): void
    {
        // Composer solo para el navigation-menu
        View::composer('components.navigation-menu', function ($view) {
            $swCambio = DB::table('distritos')
                ->select('sw_cambio')
                ->distinct()
                ->pluck('sw_cambio')
                ->first();

            $view->with('sw_cambio', $swCambio);
        });

    }
}
