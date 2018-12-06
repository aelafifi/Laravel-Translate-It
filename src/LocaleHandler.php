<?php

namespace ElMag\TranslateIt;

use Closure;
use Illuminate\Support\Facades\View;

class LocaleHandler
{
    public function handle($request, Closure $next, $code = null)
    {
        $locale_code = $code ?? session()->get('locale', 'en');
        $locale = Locale::where('code', $locale_code)->first();
        app()->setLocale($locale_code);

        View::share('locale_code', $locale_code);
        View::share('locale', $locale);

        $all_locales = Locale::all()->pluck('code')->toArray();
        config()->set('translatable.locales', $all_locales);

        return $next($request);
    }
}
