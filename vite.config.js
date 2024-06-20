import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/custom.css',
                'resources/css/theme.css',
                'resources/js/app.js',
                'resources/js/custom.js',
                'resources/js/theme.js',
            ],
            refresh: true,
        }),
    ],
});
