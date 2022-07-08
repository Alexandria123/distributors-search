<?php

namespace App\Providers;

use App\Distributors\allDistributors;
use App\Distributors\Search;
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
        $this->app->bind(AllDistributors::class, function() {
            return new AllDistributors();
        });
        $this->app->bind(Search::class, function() {
            return new Search();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
