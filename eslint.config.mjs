import js from '@eslint/js';
import flowtype from 'eslint-plugin-ft-flow';
import jest from 'eslint-plugin-jest';
import jsxA11y from 'eslint-plugin-jsx-a11y';
import react from 'eslint-plugin-react';
import reactHooks from 'eslint-plugin-react-hooks';
import globals from 'globals';
import hermesParser from 'hermes-eslint';

export default [
    js.configs.recommended,
    jest.configs['flat/recommended'],
    jest.configs['flat/style'],
    jsxA11y.flatConfigs.recommended,
    react.configs.flat.recommended,
    react.configs.flat['jsx-runtime'],
    reactHooks.configs['recommended-latest'],
    {
        plugins: {
            'ft-flow': flowtype,
        },
    },
    {
        rules: {
            ...flowtype.configs.recommended.rules,

            // Variables
            'no-unused-vars': [
                'error',
                {
                    varsIgnorePattern: 'Fragment',
                },
            ],

            // Was disabled by flowtype.configs.recommended.rules
            'no-undef': 'error',
            'ft-flow/define-flow-type': 'error',

            'ft-flow/array-style-simple-type': ['error', 'shorthand'],
            'ft-flow/newline-after-flow-annotation': ['error'],
            'ft-flow/require-indexer-name': ['error'],
            'ft-flow/require-readonly-react-props': ['error'],

            'react/button-has-type': ['error'],
            'react/no-typos': ['error'],
            'react/prop-types': ['off'],

            // JSX-specific rules
            'react/jsx-no-target-blank': ['off'],
        },

        languageOptions: {
            globals: {
                ...globals.node,
                ...globals.browser,
            },
            parser: hermesParser,
            sourceType: 'module',
        },

        settings: {
            react: {
                pragma: 'h',
                version: '18.3.1', // https://github.com/preactjs/preact/blob/main/compat/src/index.js
                flowVersion: '0.266.1',
            },
        },
    },
    {
        ignores: [
            'flow-typed/',
            'vendor/',

            'public/build/',
            'public/storage/',
            'storage/',
        ],
    },
];
