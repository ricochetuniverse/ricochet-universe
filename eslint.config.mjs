import js from '@eslint/js';
import eslintReact from '@eslint-react/eslint-plugin';
import {defineConfig} from 'eslint/config';
import importPlugin from 'eslint-plugin-import';
import jest from 'eslint-plugin-jest';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import reactHooks from 'eslint-plugin-react-hooks';
import regexp from 'eslint-plugin-regexp';
import testingLibrary from 'eslint-plugin-testing-library';
import tseslint from 'typescript-eslint';

export default defineConfig(
    js.configs.recommended,
    tseslint.configs.strict, // strictTypeChecked

    importPlugin.flatConfigs.typescript,
    jest.configs['flat/recommended'],
    jest.configs['flat/style'],
    jsxA11y.flatConfigs.recommended,
    eslintReact.configs['recommended-typescript'],
    // @ts-expect-error typescript being weird and keep erroring here
    reactHooks.configs['recommended'],
    regexp.configs['flat/recommended'],
    {
        rules: {
            'no-var': 'error',
            'prefer-const': 'warn',

            // ref as prop (https://react.dev/blog/2024/12/05/react-19#ref-as-a-prop)
            // is not supported on Preact yet
            '@eslint-react/no-forward-ref': 'off',
            '@eslint-react/no-leaked-conditional-rendering': 'error',
            '@eslint-react/prefer-read-only-props': 'error',

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

            'import/enforce-node-protocol-usage': ['error', 'always'],
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
            parserOptions: {
                projectService: true,
                tsconfigRootDir: import.meta.dirname,
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
