import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import prettier from 'eslint-config-prettier';

export default [
    js.configs.recommended,
    ...pluginVue.configs['flat/recommended'],
    prettier,
    {
        files: ['resources/js/**/*.{js,vue}'],
        languageOptions: {
            globals: {
                setTimeout: 'readonly',
                clearTimeout: 'readonly',
                console: 'readonly',
                window: 'readonly',
                document: 'readonly',
                fetch: 'readonly',
                Audio: 'readonly',
                URL: 'readonly',
                navigator: 'readonly',
            },
        },
        rules: {
            'vue/multi-word-component-names': 'off',
            'vue/no-v-html': 'off',
            'no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
        },
    },
    {
        ignores: ['vendor/**', 'node_modules/**', 'public/**'],
    },
];
