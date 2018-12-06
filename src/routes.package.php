<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'MigrationsController@index');
Route::post('/generate', 'MigrationsController@generate');
