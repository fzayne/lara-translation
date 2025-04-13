<?php

namespace Fzayne\LaraTranslation;

use Fzayne\LaraTranslation\Console\ExportTranslationKeys;
use Illuminate\Support\ServiceProvider;

class LaraTranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/lara-translation.php', 'lara-translation');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/lara-translation.php' => config_path('lara-translation.php'),
        ], 'lara-translation-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportTranslationKeys::class,
            ]);

        }
    }
}
