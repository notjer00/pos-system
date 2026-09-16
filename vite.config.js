import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1',
        strictPort: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.tsx',
                'resources/js/pages/settings/appearance.tsx',
                'resources/js/pages/settings/profile.tsx',
                'resources/js/pages/settings/security.tsx',
            ],
            refresh: true,
        }),
    ],
});
