import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dashboard.css',  // File spesifik
                'resources/js/dashboard.js',    // File spesifik
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            usePolling: true, // Enables continuous detection of VS Code edits
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
