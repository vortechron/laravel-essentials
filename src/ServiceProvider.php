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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'vtr');
    }

    public function register()
    {
        
    }

    protected function bootBlade()
    {
        Blade::include('supports.errors', 'errors');
        Blade::include('supports.alerts', 'alerts');

        Blade::directive('old', function ($expression) {
            return "<?php echo old($expression); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });
    }
}