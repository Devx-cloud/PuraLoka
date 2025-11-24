<nav id="main-navbar" class="fixed top-0 left-0 w-full z-50 shadow-xl transition-all duration-300 ease-in-out transform bg-transparent backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="#beranda" class="text-2xl font-extrabold tracking-widest transition duration-300 
            text-white hover:text-low" id="navbar-logo">
            {{ $titleApp }}
        </a>
        <div class="space-x-6 hidden sm:flex" id="navbar-links">
            <a href="#beranda" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                text-white hover:text-low hover:border-yellow-700">Beranda</a>
            <a href="#fitur-ai" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                text-white hover:text-low hover:border-yellow-700">Tools AI</a>
            <a href="#alur" class="font-semibold transition duration-200 py-1 border-b-2 border-transparent 
                text-white hover:text-low hover:border-yellow-700">Alur </a>
        </div>
        {{-- login?? --}}
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('main-navbar');
        let lastScrollTop = 0;
        const scrollThreshold = 50; 

        const featureSection = document.getElementById('fitur-ai');
        const alurSection = document.getElementById('alur');
        const navLinksContainer = document.getElementById('navbar-links');
        const navLinks = navLinksContainer ? navLinksContainer.querySelectorAll('a') : [];
        const logoLink = document.getElementById('navbar-logo');

        const classMap = {
            // ... (Kode classMap Anda di sini)
            light: {
                navbar: ['bg-white', 'shadow-lg', 'bg-opacity-95', 'backdrop-blur-none'],
                removeNavbar: ['bg-transparent', 'backdrop-blur-md', 'shadow-xl'],
                logo: ['text-mid', 'hover:text-high'],
                removeLogo: ['text-white', 'hover:text-low'],
                links: ['text-mid', 'hover:text-high', 'hover:border-high'],
                removeLinks: ['text-white', 'hover:text-low', 'hover:border-low'],
            },
            dark: {
                navbar: ['bg-transparent', 'backdrop-blur-md', 'shadow-xl'],
                removeNavbar: ['bg-white', 'shadow-lg', 'bg-opacity-95', 'backdrop-blur-none'],
                logo: ['text-white', 'hover:text-low'],
                removeLogo: ['text-mid', 'hover:text-high'],
                links: ['text-white', 'hover:text-low', 'hover:border-low'],
                removeLinks: ['text-mid', 'hover:text-high', 'hover:border-high'],
            }
        };
        // ... (Kode JavaScript Anda selanjutnya)
        const applyNavbarStyle = (styleType) => {
            const style = classMap[styleType];
            const removeStyle = classMap[styleType === 'light' ? 'dark' : 'light'];

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
            const lightThemeSections = [featureSection, alurSection].filter(el => el !== null);

            if (lightThemeSections.length === 0) {
                applyNavbarStyle('dark');
                return;
            }

            const navHeight = navbar.offsetHeight;
            let shouldBeLight = false;

            for (const section of lightThemeSections) {
                const rect = section.getBoundingClientRect();

                if (rect.top < navHeight && rect.bottom > navHeight / 2) {
                    shouldBeLight = true;
                    break;
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
                navbar.classList.add('-translate-y-full');
            } else if (scrollTop < lastScrollTop || scrollTop <= 0) {
                navbar.classList.remove('-translate-y-full');
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

            // 2. Logic Perubahan Gaya Navbar
            handleNavbarStyleChange();
        });

        handleNavbarStyleChange();
    });
</script>