import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                // Giữ nguyên tên file cho AdminLTE
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.includes('adminlte')) {
                        return 'css/[name][extname]';
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
});
