<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GeneratorController extends Controller
{
    public function index()
    {
        // Data fitur (didefinisikan dalam PHP, bukan file JSON terpisah)
        // Kita akan mengirimkannya ke view, dan view akan menggunakan @json
        $ai_tools = [
        [
        'id' => 'generate', // Parameter untuk Route
        'title' => 'Gambar ke Video Dinamis',
        'description' => 'Tambahkan gerakan, efek cuaca, atau transisi sinematik ke gambar statis Anda hanya dengan deskripsi teks.',
        'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m22 8-6 4 6 4V8Z" />
            <rect x="2" y="4" width="12" height="16" rx="2" />
        </svg>',
        ],
        [
        'id' => 'image-restoration',
        'title' => 'Restorasi Gambar (Gambar-ke-Gambar)',
        'description' => 'Pulihkan foto buram, tambal bagian yang rusak (inpainting), atau perluas latar belakang (outpainting) secara otomatis.',
        'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
            <path d="m9 12 2 2 4-4" />
        </svg>',
        ],
        [
        'id' => 'blueprint-to-video',
        'title' => 'Gambar denah-ke-video',
        'description' => 'Tambahkan gerakan, efek cuaca, atau transisi sinematik ke gambar statis Anda hanya dengan deskripsi teks.',
        'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
            <circle cx="12" cy="10" r="3" />
        </svg>',
        ],
        ];

        // Mengirim data 'features' ke view 'home'
        return view('main', compact('ai_tools'));
    }
}
