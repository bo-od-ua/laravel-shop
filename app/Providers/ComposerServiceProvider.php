<?php

namespace App\Providers;

use App\Models\Basket;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layout.patrial.roots', function ($view){
            $view->with(['items'=> Category::roots()]);
        });
        View::composer('layout.patrial.brands', function ($view){
            $view->with(['items'=> Brand::popular()]);
        });
        View::composer('layout.site', function ($view){
            $view->with(['positions'=> Basket::getCount()]);
        });
    }
}
