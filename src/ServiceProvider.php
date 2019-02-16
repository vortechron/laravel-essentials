<?php

namespace Vortechron\Essentials;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Vortechron\Essentials\Commands\Install;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        // Fix mysql issue
        Schema::defaultStringLength(191);
        
        $this->bootBlade();

        if ($this->app->runningInConsole()) {
            $this->commands([
                Install::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', config('laravel-essentials.view_namespace'));
        
        if ($this->app->runningInConsole()) {
            
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-essentials.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-essentials');
    }

    protected function bootBlade()
    {
        if (config('laravel-essentials.enable_blade_include')) {
            Blade::include(config('laravel-essentials.view_namespace') . '::supports.errors', 'errors');
            Blade::include(config('laravel-essentials.view_namespace') . '::supports.alerts', 'alerts');
        }

        Blade::directive('old', function ($expression) {
            return "<?php echo old($expression); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });
        
        Blade::directive('config', function ($expression) {
            return "<?php echo config($expression); ?>";
        });
        
        Blade::if('has', function ($variable) {
            return isset($$variable) ? $$variable : false;;
        });
    }
}
