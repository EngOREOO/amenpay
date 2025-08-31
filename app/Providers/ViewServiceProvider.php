<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\LocalizationService;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('admin.*', function ($view) {
            $localization = app(LocalizationService::class);
            
            $view->with([
                'localization' => $localization,
                'isRTL' => $localization->isRTL(),
                'direction' => $localization->getDirection(),
                'sidebarPosition' => $localization->getSidebarPosition(),
                'translations' => $localization->getAllTranslations(),
                'currentLocale' => $localization->getCurrentLocale()
            ]);
        });
    }
}
