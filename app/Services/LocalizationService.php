<?php

namespace App\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class LocalizationService
{
    protected $translations = [];
    protected $currentLocale = 'en';

    public function __construct()
    {
        $this->currentLocale = App::getLocale();
        $this->loadTranslations();
    }

    protected function loadTranslations()
    {
        $locale = $this->currentLocale;
        $path = resource_path("lang/{$locale}.json");
        
        if (File::exists($path)) {
            $this->translations = json_decode(File::get($path), true) ?? [];
        }
    }

    public function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $value = $this->translations;

        foreach ($keys as $segment) {
            if (!isset($value[$segment])) {
                return $default ?? $key;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    public function isRTL()
    {
        return $this->currentLocale === 'ar';
    }

    public function getDirection()
    {
        return $this->isRTL() ? 'rtl' : 'ltr';
    }

    public function getSidebarPosition()
    {
        return $this->isRTL() ? 'right' : 'left';
    }

    public function formatCurrency($amount, $currency = 'SAR')
    {
        $locale = $this->currentLocale === 'ar' ? 'ar-SA' : 'en-US';
        
        return (new \NumberFormatter($locale, \NumberFormatter::CURRENCY))
            ->formatCurrency($amount, $currency);
    }

    public function formatDate($date, $format = 'medium')
    {
        $locale = $this->currentLocale === 'ar' ? 'ar-SA' : 'en-US';
        
        $formatter = new \IntlDateFormatter(
            $locale,
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE
        );
        
        switch ($format) {
            case 'short':
                $formatter->setPattern($this->isRTL() ? 'dd/MM/yyyy' : 'MM/dd/yyyy');
                break;
            case 'medium':
                $formatter->setPattern($this->isRTL() ? 'dd MMM yyyy' : 'MMM dd, yyyy');
                break;
            case 'long':
                $formatter->setPattern($this->isRTL() ? 'dd MMMM yyyy' : 'MMMM dd, yyyy');
                break;
        }
        
        return $formatter->format($date);
    }

    public function getMonthName($month)
    {
        return $this->get("charts.months." . strtolower(date('M', mktime(0, 0, 0, $month, 1))));
    }

    public function getAllTranslations()
    {
        return $this->translations;
    }
}
