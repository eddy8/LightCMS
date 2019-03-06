<?php

namespace App\Providers;

use App\Repository\Admin\ConfigRepository;
use Illuminate\Support\ServiceProvider;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (config('light.light_config') === true) {
            $this->loadConfig();
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function loadConfig()
    {
        config(['light_config' => ConfigRepository::all()]);
    }
}
