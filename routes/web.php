<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Assurez-vous que `app.blade.php` inclut l'application Vue.js
})->where('any', '.*');
