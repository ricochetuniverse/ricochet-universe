import js from '@eslint/js';
import eslintReact from '@eslint-react/eslint-plugin';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import tseslint from 'typescript-eslint';

export default tseslint.config(
    js.configs.recommended,
    tseslint.configs.strict,

    jsxA11y.flatConfigs.recommended,
    eslintReact.configs['recommended-typescript'],
    reactHooks.configs['recommended-latest'],
    {
        rules: {
            // Variables
            'no-var': 'error',
            'no-unused-vars': 'error',
            'prefer-const': 'warn',

            // todo check if preact has this
            '@eslint-react/no-forward-ref': 'off',

            '@typescript-eslint/no-empty-object-type': [
                'error',
                {
                    allowObjectTypes: 'always',
                },
            ],
        },
        languageOptions: {
            globals: {
                ...globals.browser,
            },
        },
    },
    {
        files: ['**/*.js', '**/*.cjs', 'webpack.config.ts'],
        rules: {
            '@typescript-eslint/no-require-imports': 'off',
        },
        languageOptions: {
            globals: {
                ...globals.node,
            },
        },
    },
    {
        ignores: ['vendor/', 'public/build/', 'public/storage/', 'storage/'],
    }
);
