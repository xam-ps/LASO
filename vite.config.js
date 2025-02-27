import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/grossCalculator.js',
                'resources/js/toggleDepreciation.js',
                'resources/js/costTypePopup.js',
            ],
            refresh: true,
        }),
    ],
});
