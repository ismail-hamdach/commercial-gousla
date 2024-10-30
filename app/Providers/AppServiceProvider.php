<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::created(function ($product) {
            $product->update([
                'ref' => 'AZ_' . $product->id,
            ]);
        });
        
        Order::created(function ($order) {
            $order->update([
                'ref' => 'BL_' . $order->id,
            ]);
        });
        Paginator::useBootstrap();
    }
}
