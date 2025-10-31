<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/generate', function () {
    return view('generate'); // Halaman Generate Video
});
