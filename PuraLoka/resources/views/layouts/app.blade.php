<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Generator AI - Pura Loka</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/logo.png">
    <!-- @vite('resources/css/app.css') -->
    <!-- @vite(['resources/css/app.css', 'resources/js/app.js']) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* 
        emerald 300 (110 231 183)
        emerald 600 (5 150 105)
        emerald 900 (6 95 70)

        low yellow (186 114 0)
        mid yellow (235 171 57)
        high yellow (255 237 195)

        Rich Metallic Gold (184 134 11)
        Antique Gold (201 162 63)
        Muted Gold (212 175 55)
        Dark Gold (166 124 0)
        */
        .text-low {
            --tw-text-opacity: 1;
            color: rgb(186 114 0 / var(--tw-text-opacity));
        }

        .text-mid {
            --tw-text-opacity: 1;
            color: rgb(235 171 57 / var(--tw-text-opacity));
        }

        .text-high {
            --tw-text-opacity: 1;
            color: rgb(255 237 195 / var(--tw-text-opacity));
        }


        .bg-mid {
            --tw-text-opacity: 1;
            background-color: rgb(235 171 57 / var(--tw-text-opacity));
        }

        .bg-high {
            --tw-text-opacity: 1;
            background-color: rgb(255 237 195 / var(--tw-text-opacity));
        }


        .border-low {
            --tw-text-opacity: 1;
            border-color: rgb(186 114 0 / var(--tw-text-opacity));
        }

        .border-mid {
            --tw-text-opacity: 1;
            border-color: rgb(235 171 57 / var(--tw-text-opacity));
        }

        .border-high {
            --tw-text-opacity: 1;
            border-color: rgb(255 237 195 / var(--tw-text-opacity));
        }


        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white antialiased">


    @yield('content')


    <footer class="">
        <div class="bg-gradient-to-t from-white via-white to-white py-4 w-full text-center">

            <div class="max-w-7xl mx-auto px-6 lg:px-8">
    
                <div class="pb-12 grid grid-cols-1 md:grid-cols-4 gap-10 text-center md:text-left">
    
                    <div>
                        <!-- <h2 class="text-2xl font-bold tracking-tight text-black">{{ $titleApp }}</h2> -->
                        <img src="{{ asset('assets/images/lokapura-horizontal-black.png') }}" alt="Logo Pura Agung Besakih" class="w-44 h-auto mt-[-16px] mx-auto lg:ml-[-10px]" id="navbar-logo-img">
                        <p class="mt-2 text-black max-w-xs mx-auto md:mx-0">
                            Platform AI terdepan untuk kreasi video dan restorasi gambar.
                        </p>
                    </div>
    
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-black">Navigasi Cepat</h3>
                        <ul class="space-y-3 text-black">
                            <li>
                                <a href="{{ url('/#beranda') }}" class="inline-flex items-center gap-2 hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                    Beranda
                                </a>
                            </li>
    
                            <li>
                                <a href="{{ url('/#fitur-ai') }}" class="inline-flex items-center gap-2 hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="7" height="7" />
                                        <rect x="14" y="3" width="7" height="7" />
                                        <rect x="14" y="14" width="7" height="7" />
                                        <rect x="3" y="14" width="7" height="7" />
                                    </svg>
                                    Tools AI
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/#alur') }}" class="inline-flex items-center gap-2 hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="15" rx="2" ry="2" />
                                        <polyline points="17 2 12 7 7 2" />
                                    </svg>
                                    Alur Kerja
                                </a>
                            </li>
                        </ul>
                    </div>
    
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-black">Sosial Media</h3>
                        <ul class="space-y-3 text-black">
                            <!-- Instagram SVG -->
                            <li>
                                <a href="https://www.instagram.com/officialfidev/" target="_blank" class="inline-flex items-center gap-2 hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                                    </svg>
                                    Instagram
                                </a>
                            </li>
                            <!-- Linkedin SVG -->
                            <li>
                                <a href="https://www.youtube.com/@officialfidev" target="_blank" class="inline-flex items-center gap-2 hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m15 12-5 3V9l5 3z" />
                                        <rect x="2" y="3" width="20" height="18" rx="4" />
                                    </svg>
                                    Youtube
                                </a>
                            </li>
                        </ul>
                    </div>
    
                    <div>
                        <h3 class="text-lg font-bold mb-4 text-black">Hubungi Kami</h3>
                        <p class="text-black">
                            Bali, Indonesia
                            <br class="mb-1" />
                            <a href="mailto:fidevofficial@gmail.com" class="text-black hover:text-black transition-colors block mt-2">
                                fidevofficial@gmail.com
                            </a>
                            <!-- Phone Icon -->
                            <!-- <p class="mt-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-2 text-low" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                (Nomor Kontak)
                            </p> -->
                        </p>
                    </div>
                </div>
                <div class="bg-linear-to-r from-transparent via-gray-800 to-transparent h-[3px] w-full"></div>
                <p class="text-sm text-black pt-4">&copy; 2025 {{ $titleApp }} AI. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>