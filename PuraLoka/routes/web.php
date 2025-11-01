<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneratorController;

// Route::get('/', function () {
//     return view('main');
// });

Route::get('/', [GeneratorController::class, 'index']);

Route::get('/generate', function () {
    return view('AI.generate'); // Halaman Generate Video
});
