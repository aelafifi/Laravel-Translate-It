<?php

namespace ElMag\TranslateIt;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TranslateItServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->loadViewsFrom(__DIR__ . '/views', 'translateit');

        Route::group([], __DIR__ . '/routes.global.php');

        if (app()->isLocal()) {
            Route::prefix('/el-mag/translate-it')
                ->namespace('\ElMag\TranslateIt\Controllers')
                ->group(__DIR__ . '/routes.package.php');

        }
    }

    public function register()
    {
        //
    }
}
