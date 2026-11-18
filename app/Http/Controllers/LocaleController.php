<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch application locale between 'bn' and 'en'.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, ['bn', 'en'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);

            // Also store in cookie for persistence across browser sessions (1 year)
            $cookie = cookie('locale', $locale, 60 * 24 * 365);

            return redirect()->back()->withCookie($cookie);
        }

        return redirect()->back();
    }
}
