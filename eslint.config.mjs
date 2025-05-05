import js from '@eslint/js';
import eslintReact from '@eslint-react/eslint-plugin';
import importPlugin from 'eslint-plugin-import';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import tseslint from 'typescript-eslint';

export default tseslint.config(
    js.configs.recommended,
    tseslint.configs.strict,

    importPlugin.flatConfigs.typescript,
    jsxA11y.flatConfigs.recommended,
    eslintReact.configs['recommended-typescript'],
    reactHooks.configs['recommended-latest'],
    {
        plugins: {
            import: importPlugin,
        },
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

            // `importPlugin.flatConfigs.recommended` without slow rules
            // https://typescript-eslint.io/troubleshooting/typed-linting/performance/#eslint-plugin-import
            'import/export': 'error',
            'import/no-duplicates': 'warn',
            'import/no-named-as-default': 'warn',
            'import/order': [
                'warn',
                {
                    'newlines-between': 'always',
                    alphabetize: {
                        order: 'asc',
                        caseInsensitive: true,
                    },
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
