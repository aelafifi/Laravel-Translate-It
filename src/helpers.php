<?php

use ElMag\TranslateIt\Locale;

if (!function_exists('load_template')) {
    function load_template($template_name)
    {
        return file_get_contents(__DIR__ . '/templates/' . $template_name);
    }
}

if (!function_exists('real_value')) {
    function real_value($value)
    {
        try {
            return json_decode($value);
        } catch (Exception $e) {
            return $value;
        }
    }
}

if (!function_exists('locale_exists')) {
    function locale_exists($localeCode)
    {
        return Locale::where('code', $localeCode)->exists();
    }
}

if (!function_exists('change_locale')) {
    function change_locale($localeCode)
    {
        if (!function_exists($localeCode)) {
            throw new Exception('Locale `' . $localeCode . '` doesn\'t exists.');
        }
        session(['locale' => $localeCode]);
    }
}
