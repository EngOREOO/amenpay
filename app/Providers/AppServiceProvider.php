<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add custom Blade directive for translations
        Blade::directive('trans', function ($expression) {
            return "<?php echo __('$expression'); ?>";
        });

        // Add custom Blade directive for dynamic translations with fallback
        Blade::directive('transDynamic', function ($expression) {
            return "<?php echo __('$expression') ?: '$expression'; ?>";
        });

        // Add custom Blade directive for language-aware content
        Blade::directive('langContent', function ($expression) {
            return "<?php echo App::getLocale() === 'ar' ? __('$expression') : __('$expression'); ?>";
        });
    }
}
