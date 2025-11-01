<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Generator AI - [Nama Website]</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    
    @yield('content')

    <!-- <div class="bg-linear-to-r from-transparent via-emerald-600 to-transparent h-[1px] w-full"></div> -->
    <footer class="bg-gradient-to-r from-emerald-800 to-emerald-700 pt-4 pb-4 w-full text-center">
        <p class="text-sm text-gray-100">&copy; 2025 Budaya Loka AI. All rights reserved.</p>
    </footer>
</body>
</html>