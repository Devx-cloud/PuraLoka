<?php
// require __DIR__ . '/../public/index.php';

// VERCEL HACK: Define a writable storage path
if (!defined('LARAVEL_STORAGE_PATH')) {
    define('LARAVEL_STORAGE_PATH', env('STORAGE_PATH', __DIR__ . '/../storage'));
}

// 1. Setup Environment
// Set the correct storage path for Vercel's read-only filesystem
$storage_path = LARAVEL_STORAGE_PATH;
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


// 2. Load Laravel Application
require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

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