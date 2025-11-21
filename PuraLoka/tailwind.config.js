/** @type {import('tailwindcss').Config} */
export default {
    content: [
        // Tambahkan path berikut untuk Laravel Blade
        './resources/**/*.blade.php', 
        // Tambahkan path ini untuk JavaScript dan Vue
        './resources/**/*.js',
        './resources/**/*.vue',
        './app/View/Components/*.php', // Jika Anda menggunakan komponen Blade
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}