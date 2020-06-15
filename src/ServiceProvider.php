<?php

namespace Vortechron\Essentials;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Vortechron\Essentials\Core\Turbolinks;
use Vortechron\Essentials\Commands\Install;
use Barryvdh\StackMiddleware\StackMiddleware;
use Illuminate\Contracts\Routing\ResponseFactory;
use Vortechron\Essentials\Commands\GenerateCountries;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    protected $formFields = [
        'select', 'input', 'richtextarea', 'textarea', 'checkbox'
    ];

    protected $components = [
        'images'
    ];

    public function boot(ResponseFactory $factory, Filesystem $filesystem)
    {
        // Fix mysql issue
        Schema::defaultStringLength(191);
        
        $this->bootBlade();

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCountries::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../database/migrations/create_essentials_table.php' => $this->getMigrationFileName($filesystem)
        ], 'migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', config('laravel-essentials.view_namespace'));
        
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-essentials.php'),
            ], 'config');
        }

        Route::get(
            'countries/flags/file/{cca3}.svg',
            'PragmaRX\CountriesLaravel\Package\Http\Controllers\Flag@file'
        )->name('countries.flags.file');
        Route::get(
            'countries/flags/download/{cca3}.svg',
            'PragmaRX\CountriesLaravel\Package\Http\Controllers\Flag@download'
        )->name('countries.flags.download');

        Route::post('/permission-check/{name}', 'Vortechron\Essentials\Http\Controllers\MediaUploadController@upload')->name('media.upload');
        Route::middleware('web')
        ->group(function () {
            Route::post('/media-upload', 'Vortechron\Essentials\Http\Controllers\MediaUploadController@upload')->name('media.upload');
            Route::get('/media-upload-manager', 'Vortechron\Essentials\Http\Controllers\MediaUploadController@uploadManagerIndex')->name('media.uploadManagerIndex');
            Route::post('/media-upload-manager', 'Vortechron\Essentials\Http\Controllers\MediaUploadController@uploadManager')->name('media.uploadManager');

            Route::post('/verify-check', 'Vortechron\Essentials\Http\Controllers\PhoneVerificationController@check')->name('verify.check');
            Route::post('/verify-send', 'Vortechron\Essentials\Http\Controllers\PhoneVerificationController@send')->name('verify.send');
            Route::post('/verify-code', 'Vortechron\Essentials\Http\Controllers\PhoneVerificationController@code')->name('verify.code');

            Route::get('/instagram/authorize', 'Vortechron\Essentials\Http\Controllers\InstagramController@auth')->name('instagram.auth');
            Route::get('/instagram/redirect', 'Vortechron\Essentials\Http\Controllers\InstagramController@redirect')->name('instagram.redirect');
            Route::get('/instagram/import', 'Vortechron\Essentials\Http\Controllers\InstagramController@import')->name('instagram.import');
        });

        $factory->macro('makeWithTurbolinks', function ($content, $options = []) use ($factory) {
            $status =  array_pull($options, 'status', 200);
            $headers = array_pull($options, 'headers', []);

            $turbolinksHeaders = app('turbolinks')->convertTurbolinksOptions($options);
            $headers = array_merge($headers, $turbolinksHeaders);

            return $factory->make($content, $status, $headers);
        });

        $factory->macro('redirectToWithTurbolinks', function ($path, $options = []) use ($factory) {
            $status =  array_pull($options, 'status', 302);
            $headers = array_pull($options, 'headers', []);
            $secure =  array_pull($options, 'secure');

            $turbolinksHeaders = app('turbolinks')->convertTurbolinksOptions($options);
            $headers = array_merge($headers, $turbolinksHeaders);

            return $factory->redirectTo($path, $status, $headers, $secure);
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-essentials');

        $this->app->singleton('turbolinks', function ($app) {
            return new Turbolinks;
        });
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
        
        if (config('laravel-essentials.enable_error')) {
            Blade::include($namespace .'::components.errors', 'error');
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

        Blade::include($namespace .'::components.essentials', 'essentials');
        Blade::include($namespace .'::components.meta', 'meta');
    }

    protected function getMigrationFileName(Filesystem $filesystem): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem) {
                return $filesystem->glob($path.'*_create_permission_tables.php');
            })->push($this->app->databasePath()."/migrations/{$timestamp}_create_essentials_table.php")
            ->first();
    }
}
