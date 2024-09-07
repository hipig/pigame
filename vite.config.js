import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import Components from 'unplugin-vue-components/vite';
import {PrimeVueResolver} from '@primevue/auto-import-resolver';
import UnoCSS from 'unocss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/ts/main.ts'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vueJsx(),
        Components({
            resolvers: [
                PrimeVueResolver()
            ]
        }),
        UnoCSS()
    ],
    resolve: {
        alias: {
            '@': '/resources/ts'
        },
    }
});
