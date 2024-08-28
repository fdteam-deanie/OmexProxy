import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/reset.css',
                'resources/css/fonts/fonts.css',
                'resources/css/style.css',
                'resources/css/auth.css',
                'resources/sass/app-new.scss',
                'resources/scss/style.scss',
                'resources/js/app.js',
                'resources/js/auth/mfa.js'
            ],
            refresh: true,
            transformOnServe: (code, devServerUrl) => code.replaceAll('/img', devServerUrl+'/public/img'),
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
