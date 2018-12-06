<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use ElMag\TranslateIt\Locale;

Route::get('/locale/{code}', function (Request $request, $code) {
    Locale::where('code', $code)->firstOrFail();
    session(['locale' => $code]);
    return redirect()->back();
})->name('change_locale');
