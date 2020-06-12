<?php

namespace App\Providers;

use App\Repository\Admin\ConfigRepository;
use Illuminate\Support\Facades\App;
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
        if (config('light.light_config') === true && !App::environment('testing')) {
            $this->loadConfig();
        }

        /*
        if (\App::environment('dev')) {
            \DB::listen(function ($sql) {
                $sqlStr = $sql->sql;
                foreach ($sql->bindings as $replace) {
                    $value = is_numeric($replace) ? $replace : "'" . $replace . "'";
                    $sqlStr = preg_replace('/\?/', $value, $sqlStr, 1);
                }
                \Log::debug(PHP_EOL . 'SQL：' . $sqlStr . PHP_EOL . '用时：' . $sql->time . 'ms');
                return true;
            });
        }
        */
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
        foreach (config('light_config') as $key => $value) {
            config(["light_config.{$key}" => parseConfig($value)]);
        }
    }
}
