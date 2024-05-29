<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $validatorFiles = scandir(app_path('Validators'));

        foreach ($validatorFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'php') {
                $class = 'App\\Validators\\' . pathinfo($file, PATHINFO_FILENAME);
                $class::extendValidator();
            }
        }
    }
}
