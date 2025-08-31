<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch(Request $request, $locale)
    {
        // Validate locale
        $validLocales = ['en', 'ar'];
        if (!in_array($locale, $validLocales)) {
            $locale = 'en';
        }

        // Set locale in session
        Session::put('locale', $locale);
        
        // Set locale in app
        App::setLocale($locale);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Language changed successfully');
    }
}
