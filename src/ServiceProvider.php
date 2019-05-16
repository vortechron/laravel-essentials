<?php

namespace Vortechron\Essentials;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Vortechron\Essentials\Commands\Install;

class ServiceProvider extends BaseServiceProvider
{
    protected $formFields = [
        'select', 'input', 'richtextarea', 'textarea', 'checkbox'
    ];

    protected $components = [
        'images'
    ];

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
        $namespace = config('laravel-essentials.view_namespace');

        if (config('laravel-essentials.enable_blade_include')) {
            Blade::include(config('laravel-essentials.view_namespace') . '::supports.errors', 'errors');
            Blade::include(config('laravel-essentials.view_namespace') . '::supports.alerts', 'alerts');
        }
        
        if (config('laravel-essentials.enable_blade_components')) {

            Blade::directive('indexer', function ($expression) use ($namespace) {
                return "<?php echo app('view')->make('{$namespace}::components.indexer', ['model' => $expression]) ?>";
            });

            foreach ($this->formFields as $field) {
                Blade::include($namespace .'::components.formfields.'. $field, $field);
            }

            foreach ($this->components as $comp) {
                Blade::include($namespace .'::components.'. $comp, $comp);
            }
            
        }
            

        Blade::include($namespace .'::components.errors', 'error');

        Blade::directive('old', function ($expression) {
            return "<?php echo old($expression); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route($expression); ?>";
        });
        
        Blade::directive('config', function ($expression) {
            return "<?php echo config($expression); ?>";
        });
        
        Blade::directive('declareWithDefault', function ($expression) {
            list($variable, $default) = explode(',',str_replace(['(',')',' ', "'"], '', $expression));
            
            return "<?php 
            $$variable = isset($$variable) ? $$variable : '$default';
            ?>";
        });
        
        foreach (['null', 'false', 'true'] as $value) {
            Blade::directive('declare'. ucfirst($value), function ($expression) use ($value) {
                return "<?php 

                foreach([$expression] as \$arg) {
                    $\$arg = isset($\$arg) ? $\$arg : $value;
                }
                
                ?>";
            });
        }
        
    }
}
