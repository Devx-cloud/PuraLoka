<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('titleApp', 'Loka Pura'); // Simpan nama di variabel $developerName
        });

        // --- LOGIKA FIX URL UNTUK TUNNEL/PROXY (DIREVISI) ---

    $appUrl = config('app.url');
    $isSecure = str_contains($appUrl, 'https');
    
    // Opsi A: Cek apakah request sedang di-tunnel/proxy
    // Jika ada HTTP_HOST dan APP_URL masih default (localhost), gunakan host header.
    if (isset($_SERVER['HTTP_HOST']) && 
        ($appUrl === 'http://localhost' || $appUrl === 'http://localhost:8000')) {
        
        // Cek juga apakah proxy memaksa HTTPS
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            $isSecure = true;
        }

        $protocol = $isSecure ? 'https' : 'http';
        $fullUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];
        
        // PENTING: Gunakan Host Header untuk Force Root URL
        URL::forceRootUrl($fullUrl);
        
    } else if ($appUrl) {
        // Opsi B: Gunakan APP_URL dari .env jika Host Header tidak terdeteksi (seperti saat di Production Vercel)
        URL::forceRootUrl($appUrl);
    }
    
    // Selalu paksa skema HTTPS jika APP_URL atau header menunjukkan HTTPS
    if ($isSecure) {
        URL::forceScheme('https');
    }
    }
}
