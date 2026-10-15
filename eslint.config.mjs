import js from '@eslint/js';
import eslintReact from '@eslint-react/eslint-plugin';
import tsParser from '@typescript-eslint/parser';
import {defineConfig} from 'eslint/config';
import {importX} from 'eslint-plugin-import-x';
import jest from 'eslint-plugin-jest';
import jsxA11yX from 'eslint-plugin-jsx-a11y-x';
import reactHooks from 'eslint-plugin-react-hooks';
import regexp from 'eslint-plugin-regexp';
import testingLibrary from 'eslint-plugin-testing-library';
import tseslint from 'typescript-eslint';

export default defineConfig(
    js.configs.recommended,
    tseslint.configs.strictTypeChecked,

    importX.flatConfigs.typescript,
    eslintReact.configs['strict-type-checked'],
    reactHooks.configs.flat.recommended,
    jsxA11yX.configs.strict,
    jest.configs['flat/recommended'],
    jest.configs['flat/style'],
    regexp.configs.recommended,
    {
        rules: {
            'no-var': 'error',
            'prefer-const': 'warn',

            '@eslint-react/immutability': 'error',
            '@eslint-react/prefer-destructuring-assignment': 'off',

            // `importX.flatConfigs.recommended` without slow rules
            // https://typescript-eslint.io/troubleshooting/typed-linting/performance/#eslint-plugin-import
            'import-x/export': 'error',
            'import-x/no-named-as-default': 'warn',
            'import-x/no-duplicates': 'warn',

            // 'import/enforce-node-protocol-usage': ['error', 'always'],
            'import-x/order': [
                'warn',
                {
                    'newlines-between': 'always',
                    alphabetize: {
                        order: 'asc',
                        caseInsensitive: true,
                    },
                    named: true,
                },
            ],
        },
        languageOptions: {
            parserOptions: {
                parser: tsParser,
                projectService: true,
                tsconfigRootDir: import.meta.dirname,
            },
        },
        settings: {
            jest: {
                // https://github.com/jest-community/eslint-plugin-jest#aliased-jestglobals
                globalPackage: '@rstest/core',
                version: 30,
            },
        },
    },
    {
        files: ['**/?(*.)+(spec|test).[jt]s?(x)'],
        ...testingLibrary.configs['flat/react'],
    },
    {
        ignores: [
            'vendor/',
            'public/build/',
            'public/storage/',
            'storage/',
            'coverage/',
        ],
    }
);
