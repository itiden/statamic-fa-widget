import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/cp.js',
            ],
            publicDirectory: '/vendor/statamic-fa-widget',
        }),
        vue({
          // This is needed, or else Vite will try to find image paths (which it wont be able to find because this will be called on the web, not directly)
          // For example <img src="/images/logo.png"> will not work without the code below
          template: {
              transformAssetUrls: {
                  base: null,
                  includeAbsolute: false,
              },
          },
        }),
    ],
});
