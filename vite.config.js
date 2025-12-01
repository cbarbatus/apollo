import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({

    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // The publicDirectory option tells the Laravel plugin where your public folder is.
            publicDirectory: 'public_html', // <-- ADD/KEEP THIS IN LARAVEL PLUGIN
            buildDirectory: 'build',       // <-- ADD/KEEP THIS IN LARAVEL PLUGIN
            refresh: true,
        }),
    ],
    // ADD THIS BLOCK to define the build output directory
    build: {
        // outDir is relative to the project root, so this specifies the full path:
        outDir: 'public_html/build',
        emptyOutDir: true,
    },
});
