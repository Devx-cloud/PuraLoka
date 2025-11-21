<?php

// 1. Load Laravel Application (Autoloading dan Bootstrap harus duluan)
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// PENTING: Environment Variables (Env) harus di load di sini.
// Sekarang fungsi env() sudah dikenal.

// VERCEL HACK: Define a writable storage path
// Pindahkan define() ke sini, setelah app dimuat.
if (!defined('LARAVEL_STORAGE_PATH')) {
    // Baris ini sekarang aman karena env() sudah terdefinisi.
    define('LARAVEL_STORAGE_PATH', env('STORAGE_PATH', __DIR__ . '/../storage'));
}

// Set the correct storage path for Vercel's read-only filesystem
$storage_path = LARAVEL_STORAGE_PATH;

// 2. Setup Writable Directories (Perlu dilakukan SETELAH $storage_path didefinisikan)
if (!is_dir($storage_path . '/framework/cache/data')) {
    mkdir($storage_path . '/framework/cache/data', 0755, true);
}
if (!is_dir($storage_path . '/framework/sessions')) {
    mkdir($storage_path . '/framework/sessions', 0755, true);
}
if (!is_dir($storage_path . '/framework/views')) {
    mkdir($storage_path . '/framework/views', 0755, true);
}
if (!is_dir($storage_path . '/logs')) {
    mkdir($storage_path . '/logs', 0755, true);
}

// Bind the storage path to the Application
$app->bind('path.storage', function () use ($storage_path) {
    return $storage_path;
});

// 3. Handle Request
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);