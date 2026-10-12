import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            // Cores ligadas a variáveis CSS (resources/css/app.css) => dark/light automático
            colors: {
                bg: 'var(--bg)',
                surface: 'var(--surface)',
                soft: 'var(--soft)',
                ink: 'var(--ink)',
                mute: 'var(--mute)',
                line: 'var(--line)',
                brand: 'var(--brand)',
                brandink: 'var(--brandink)',
                accent: 'var(--accent)',
            },
            fontFamily: {
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};