import js from '@eslint/js';
import eslintReact from '@eslint-react/eslint-plugin';
import importPlugin from 'eslint-plugin-import';
import jest from 'eslint-plugin-jest';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import testingLibrary from 'eslint-plugin-testing-library';
import globals from 'globals';
import tseslint from 'typescript-eslint';

export default tseslint.config(
    js.configs.recommended,
    tseslint.configs.strict,

    importPlugin.flatConfigs.typescript,
    jest.configs['flat/recommended'],
    jest.configs['flat/style'],
    jsxA11y.flatConfigs.recommended,
    eslintReact.configs['recommended-typescript'],
    reactHooks.configs['recommended-latest'],
    {
        plugins: {
            import: importPlugin,
        },
        rules: {
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
        files: ['**/*.js', '**/*.cjs'],
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
        files: ['**/?(*.)+(spec|test).[jt]s?(x)'],
        ...testingLibrary.configs['flat/react'],
    },
    {
        ignores: ['vendor/', 'public/build/', 'public/storage/', 'storage/'],
    }
);
