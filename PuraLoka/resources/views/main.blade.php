@extends('layouts.app')

@section('content')


<!-- 
        =============================================
        NAVBAR (Fixed for In-Page Navigation)
        =============================================
    -->
<nav id="main-navbar" class="fixed top-0 left-0 w-full z-50 shadow-xl 
          transition-all duration-300 ease-in-out transform
          bg-transparent backdrop-blur-md"> {{-- Default: Transparan blur --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo/Nama Website -->
         <a href="#beranda" class="text-2xl font-extrabold tracking-widest transition duration-300 
                text-white hover:text-emerald-300" id="navbar-logo"> {{-- Default text-white --}}
            Budaya Loka
        </a>
        <!-- Nav Links -->
        <div class="space-x-6 hidden sm:flex" id="navbar-links">
            <a href="#beranda" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                    text-white hover:text-emerald-300 hover:border-emerald-300">Beranda</a> {{-- Default text-white --}}
            <a href="#fitur-ai" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                    text-white hover:text-emerald-300 hover:border-emerald-300">Tools AI</a>
            <a href="#alur" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                    text-white hover:text-emerald-300 hover:border-emerald-300">Alur </a>

        </div>
        {{-- login?? --}}
    </div>
</nav>

<!-- 
        =============================================
        1. HERO SECTION (Fokus Utama: Prompt ke Video)
        =============================================
    -->
<section id="beranda" class="
    bg-gradient-to-br from-emerald-950 to-emerald-800 text-white shadow-2xl 
    min-h-screen flex items-center pt-16 {{-- Kunci: Mengaktifkan flex dan centering vertikal --}}
">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center w-full"> {{-- w-full memastikan div mengisi lebar untuk center horizontal --}}

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
</section>

<!-- 
        =============================================
        2. FEATURE CARDS (2 Route Utama)
        =============================================
    -->

<section id="fitur-ai" class="py-20 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-5xl font-bold text-center mb-16 text-gray-900">
            TOOLS <span class="text-emerald-600">AI</span>
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mx-auto">

            {{-- Loop Tunggal untuk Membuat Semua Card --}}
            @foreach($ai_tools as $tool)
            {{-- Perhatikan: URL diarahkan ke /generate/ID_ALAT --}}
            <a href="{{ url('/' . $tool['id']) }}"
                class="group block p-8 rounded-2xl shadow-xl hover:shadow-2xl transition duration-300 transform hover:scale-[1.03] border-4 border-transparent hover:border-emerald-600 bg-white">

                <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-emerald-100 text-emerald-600">
                    {{-- Icon SVG diambil dari data --}}
                    {!! $tool['icon_svg'] !!}
                </div>

                <h3 class="text-2xl font-bold mt-2 mb-3 text-gray-900 group-hover:text-emerald-600">
                    {{ $tool['title'] }}
                </h3>

                <p class="text-gray-600">
                    {{ $tool['description'] }}
                </p>
            </a>
            @endforeach

        </div>
    </div>
</section>
<!-- <div class="bg-linear-to-r from-transparent via-emerald-600 to-transparent h-[2px] w-full"></div> -->
<!-- 
        =============================================
        3. HOW IT WORKS (Cara Kerjanya)
        =============================================
    -->

<section id="alur" class="py-20 md:py-32 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">

        <h2 class="text-4xl lg:text-5xl font-extrabold text-center mb-16 lg:mb-24 text-gray-900 leading-tight">
            Alur Kerja Cepat. <span class="text-emerald-600">Semudah Tiga Langkah.</span>
        </h2>

        <div class="relative">
            <div class="hidden md:block absolute w-1 h-full bg-emerald-200 left-1/2 transform -translate-x-1/2 top-0"></div>

            {{-- --------------------------------- LANGKAH 1 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 1, 'reverse' => false])

            {{-- --------------------------------- LANGKAH 2 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 2, 'reverse' => true])

            {{-- --------------------------------- LANGKAH 3 --------------------------------- --}}
            @include('partials.stepFlow', ['step' => 3, 'reverse' => false])

        </div>
    </div>

</section>
<!-- 
        =============================================
        JAVASCRIPT for Hiding/Showing & Dynamic Styling Navbar on Scroll
        =============================================
    -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('main-navbar');
        let lastScrollTop = 0;
        const scrollThreshold = 50; // Jarak scroll minimal sebelum navbar hide/show

        // Dapatkan elemen yang akan diubah gayanya
        const featureSection = document.getElementById('fitur-ai');
        const alurSection = document.getElementById('alur');
        const navLinksContainer = document.getElementById('navbar-links');
        const navLinks = navLinksContainer ? navLinksContainer.querySelectorAll('a') : [];
        const logoLink = document.getElementById('navbar-logo');

        const classMap = {
            // Kelas untuk navbar terang (di atas section putih)
            light: {
                navbar: ['bg-white', 'shadow-lg', 'bg-opacity-95', 'backdrop-blur-none'],
                // navbar: ['bg-transparent', 'backdrop-blur-md', 'shadow-xl'],
                removeNavbar: ['bg-transparent', 'backdrop-blur-md', 'shadow-xl'],
                logo: ['text-emerald-600', 'hover:text-emerald-800'],
                removeLogo: ['text-white', 'hover:text-emerald-300'],
                links: ['text-emerald-600', 'hover:text-emerald-800', 'hover:border-emerald-800'],
                removeLinks: ['text-white', 'hover:text-emerald-300', 'hover:border-emerald-300'],
            },
            // Kelas untuk navbar gelap (di atas section gelap/transparan)
            dark: {
                navbar: ['bg-transparent', 'backdrop-blur-md', 'shadow-xl'],
                removeNavbar: ['bg-white', 'shadow-lg', 'bg-opacity-95', 'backdrop-blur-none'],
                logo: ['text-white', 'hover:text-emerald-300'],
                removeLogo: ['text-emerald-600', 'hover:text-emerald-800'],
                links: ['text-white', 'hover:text-emerald-300', 'hover:border-emerald-300'],
                removeLinks: ['text-emerald-600', 'hover:text-emerald-800', 'hover:border-emerald-800'],
            }
        };

        const applyNavbarStyle = (styleType) => {
            const style = classMap[styleType];
            const removeStyle = classMap[styleType === 'light' ? 'dark' : 'light']; // Kebalikan dari style yang akan diterapkan

            // Navbar
            navbar.classList.remove(...removeStyle.navbar);
            navbar.classList.add(...style.navbar);

            // Logo
            if (logoLink) {
                logoLink.classList.remove(...removeStyle.logo);
                logoLink.classList.add(...style.logo);
            }

            // Links
            navLinks.forEach(link => {
                link.classList.remove(...removeStyle.links);
                link.classList.add(...style.links);
            });
        };

        const handleNavbarStyleChange = () => {
            // Kumpulkan semua section yang harus memicu theme 'light'
            const lightThemeSections = [featureSection, alurSection].filter(el => el !== null);

            // Jika tidak ada section yang terdeteksi, gunakan default 'dark'
            if (lightThemeSections.length === 0) {
                applyNavbarStyle('dark');
                return;
            }

            const navHeight = navbar.offsetHeight;
            let shouldBeLight = false;

            // Cek apakah ada SATU SAJA section theme 'light' yang sedang 'intersect'
            for (const section of lightThemeSections) {
                const rect = section.getBoundingClientRect();

                // Logika: Section dianggap aktif (light) jika bagian atasnya sudah melewati 
                // ketinggian navbar, DAN bagian bawahnya belum sepenuhnya melewati
                if (rect.top < navHeight && rect.bottom > navHeight / 2) {
                    shouldBeLight = true;
                    break; // Jika sudah menemukan satu, langsung keluar dari loop
                }
            }

            if (shouldBeLight) {
                applyNavbarStyle('light');
            } else {
                applyNavbarStyle('dark');
            }
        };

        // --- LISTENER UTAMA ---

        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // 1. Logic Hide/Show
            if (scrollTop > lastScrollTop && scrollTop > scrollThreshold) {
                // Hide Navbar
                navbar.classList.add('-translate-y-full');
            } else if (scrollTop < lastScrollTop || scrollTop <= 0) {
                // Show Navbar (scroll ke atas atau di paling atas)
                navbar.classList.remove('-translate-y-full');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

            // 2. Logic Perubahan Gaya Navbar
            handleNavbarStyleChange();
        });

        // Jalankan sekali saat load untuk mengatur gaya awal
        handleNavbarStyleChange();
    });
</script>

@endsection