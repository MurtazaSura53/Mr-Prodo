import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/style.css',
                'resources/css/auth/signup.css',
                'resources/css/auth/login.css',

                'resources/js/app.js',
                'resources/js/main.js',
                'resources/js/validators/SignupValidator.js',
                'resources/js/auth/signup.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,

        cors: {
            origin: 'http://192.168.0.101:8000',
        },

        hmr: {
            host: '192.168.0.101',
        },
        // watch: {
        //     ignored: ['**/storage/framework/views/**'],
        // },
    },
});
