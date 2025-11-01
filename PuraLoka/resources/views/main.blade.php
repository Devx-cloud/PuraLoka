@extends('layouts.app')

@section('content')

    <!-- 
        =============================================
        1. HERO SECTION (Fokus Utama: Prompt ke Video)
        =============================================
    -->
    <header class="bg-gradient-to-br from-emerald-950 to-emerald-800 text-white py-20 md:py-26 shadow-2xl">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            
            <p class="text-xl font-semibold text-emerald-300 tracking-wider uppercase mb-10">
                Budaya Loka AI
            </p>
            
            <h1 class="text-5xl sm:text-7xl md:text-8xl font-extrabold leading-tight tracking-tighter text-emerald-100">
                Seni Cipta Digital. <br class="hidden md:inline"> Dari Pikiran ke Video.
            </h1>
            
            <p class="mt-8 text-xl text-emerald-200 max-w-4xl mx-auto">
                Platform AI terdepan yang memberdayakan arsitektur pura. hidupkan foto menjadi <span class="font-bold">video dinamis</span>, atau <span class="font-bold">restorasi</span> kenangan lama dengan akurasi tinggi.
            </p>

            <div class="mt-12 pt-6">
                <svg class="w-10 h-10 mx-auto text-emerald-300 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </div>
    </header>

    <!-- 
        =============================================
        2. FEATURE CARDS (2 Route Utama)
        =============================================
    -->
        <div class="bg-linear-to-r from-transparent via-emerald-600 to-transparent h-[3px] w-full"></div>
    <section class="py-20 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">
                Pilih Kekuatan AI yang Anda Butuhkan
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mx-auto">
                
                {{-- Card 1: Prompt to Video --}}
                <a href="{{ url('/generate') }}" class="group block p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.03] border-4 border-transparent hover:border-emerald-600 bg-white">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-emerald-100 text-emerald-600">
                        <!-- Inline SVG: Video Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 8-6 4 6 4V8Z"/><rect x="2" y="4" width="12" height="16" rx="2"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-2 mb-3 text-gray-900 group-hover:text-emerald-600">
                        Gambar ke Video Dinamis
                    </h3>
                    <p class="text-gray-600">
                        Tambahkan gerakan, efek cuaca, atau transisi sinematik ke gambar statis Anda hanya dengan deskripsi teks.
                    </p>
                </a>

                {{-- Card 2: Image to Image (Restoration) --}}
                <a href="{{ url('/generate') }}" class="group block p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.03] border-4 border-transparent hover:border-emerald-600 bg-white">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-emerald-100 text-emerald-600">
                        <!-- Inline SVG: Edit/Repair Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-2 mb-3 text-gray-900 group-hover:text-emerald-600">
                        Restorasi Gambar (Gambar-ke-Gambar)
                    </h3>
                    <p class="text-gray-600">
                        Pulihkan foto buram, tambal bagian yang rusak (inpainting), atau perluas latar belakang (outpainting) secara otomatis.
                    </p>
                </a>
                
                {{-- Card 3: Image to Image (Restoration) --}}
                <a href="{{ url('/generate') }}" class="group block p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.03] border-4 border-transparent hover:border-emerald-600 bg-white">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-emerald-100 text-emerald-600">
                        <!-- Inline SVG: Edit/Repair Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold mt-2 mb-3 text-gray-900 group-hover:text-emerald-600">
                        Gambar denah-ke-video
                    </h3>
                    <p class="text-gray-600">
                        Tambahkan gerakan, efek cuaca, atau transisi sinematik ke gambar statis Anda hanya dengan deskripsi teks.
                    </p>
                </a>
            </div>
        </div>
    </section>
    <div class="bg-linear-to-r from-transparent via-emerald-600 to-transparent h-[2px] w-full"></div>
    <!-- 
        =============================================
        3. HOW IT WORKS (Cara Kerjanya)
        =============================================
    -->
    <section class="py-20 md:py-24 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">
                Alur Kerja Cepat. <span class="text-emerald-600">Semudah Tiga Langkah.</span>
            </h2>

            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Decorative Line (only visible on large screens) -->
                <div class="absolute hidden md:block w-2/3 h-0.5 bg-emerald-200 top-16 left-1/2 transform -translate-x-1/2"></div>
                
                <!-- Step 1 -->
                <div class="text-center p-6 rounded-xl relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-emerald-600 text-white shadow-2xl mb-4">
                        <span class="text-3xl font-extrabold">1</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4 mb-3 text-gray-900">Unggah Aset Anda</h3>
                    <p class="text-gray-600">Pilih gambar atau foto yang ingin Anda proses (baik untuk Video, Generasi, atau Restorasi).</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center p-6 rounded-xl relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-emerald-600 text-white shadow-2xl mb-4">
                        <span class="text-3xl font-extrabold">2</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4 mb-3 text-gray-900">Berikan Prompt/Deskripsi</h3>
                    <p class="text-gray-600">Tuliskan instruksi atau gaya kreatif yang Anda inginkan. Semakin spesifik, semakin baik hasilnya.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center p-6 rounded-xl relative z-10">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto rounded-full bg-emerald-600 text-white shadow-2xl mb-4">
                        <span class="text-3xl font-extrabold">3</span>
                    </div>
                    <h3 class="text-2xl font-bold mt-4 mb-3 text-gray-900">Dapatkan Hasil AI</h3>
                    <p class="text-gray-600">AI kami akan memproses permintaan Anda, dan hasilnya siap Anda unduh dalam hitungan detik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 
        =============================================
        4. AI WORKFLOW EXAMPLE (Contoh Gambar Kerja)
        =============================================
    -->
    <section class="py-20 md:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-900">
                Lihat Hasil Kerjanya: Proses Gambar ke Video
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start max-w-7xl mx-auto">
                
                <!-- Kolom 1: Input Gambar -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-lg border-t-4 border-emerald-500 text-center">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">1. Input Gambar Statis</h3>
                    <div class="h-64 w-full bg-gray-200 rounded-lg flex items-center justify-center mb-4 overflow-hidden">
                        {{-- Placeholder Gambar Input --}}
                        <p class="text-gray-500 font-semibold"></p>
                    </div>
                    <p class="text-sm text-gray-500">Foto landscape pedesaan di sore hari.</p>
                </div>
                
                <!-- Kolom 2: Prompt dan Proses -->
                <div class="bg-emerald-50 p-6 rounded-xl shadow-2xl border-t-4 border-emerald-700 text-center relative">
                    <!-- Panah Menunjuk ke Output (Desktop View) -->
                    <div class="hidden lg:block absolute right-0 top-1/2 transform translate-x-1/2 -translate-y-1/2">
                        <svg class="w-10 h-10 text-emerald-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold mb-4 text-emerald-800">2. Prompt & Proses AI</h3>
                    <div class="p-4 bg-white rounded-lg border-2 border-emerald-300 shadow-inner">
                        <p class="font-mono text-xs text-left text-gray-700">
                            "Tambahkan efek matahari terbenam yang bergerak, kabut tipis di lembah, dan air sungai mengalir."
                        </p>
                    </div>
                    <div class="mt-4 flex items-center justify-center space-x-2 text-emerald-600 font-semibold">
                        <!-- Inline SVG: Cog Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m4.93 19.07 1.41-1.41"/><path d="m17.66 6.34 1.41-1.41"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>AI Bekerja...</span>
                    </div>
                </div>
                
                <!-- Kolom 3: Output Video -->
                <div class="bg-gray-50 p-6 rounded-xl shadow-lg border-t-4 border-emerald-500 text-center">
                    <h3 class="text-xl font-bold mb-4 text-gray-800">3. Hasil Akhir (Video)</h3>
                    <div class="h-64 w-full bg-gray-200 rounded-lg flex items-center justify-center mb-4 overflow-hidden">
                        {{-- Placeholder Video Output --}}
                        <p class="text-emerald-500 font-semibold">
                            
                            </p>
                        </div>
                        <p class="text-sm text-gray-500 font-bold text-emerald-600">Video siap diunduh (4 detik)</p>
                    </div>
                </div>
                
            </div>
        </section>
        <!-- 
            =============================================
            5. 
            =============================================
        -->
        
        
        
        @endsection
        