<?php

namespace Penobit\CrashReporter\Providers;

use Illuminate\Support\ServiceProvider as _ServiceProvider;

class ServiceProvider extends _ServiceProvider {
    public function register(): void {
        $this->mergeConfigFrom(__DIR__.'/../../config/crash-reporter.php', 'crash-reporter');
    }

    public function boot(): void {
        $this->publishes([
            __DIR__.'/../../config/crash-reporter.php' => config_path('crash-reporter.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'crash-reporter');
    }
}