// @ts-check

'use strict';

/** @type {import('jest').Config} */
const config = {
    // extensionsToTreatAsEsm: ['.ts', '.tsx'],
    moduleNameMapper: {
        '^react$': 'preact/compat',
        '^react-dom$': 'preact/compat',
    },
    testEnvironment: 'jsdom',
    testPathIgnorePatterns: [
        '/node_modules/',

        // todo module loading trouble
        'resources/js/round-info/',
    ],
    transform: {
        '^.+\\.m?(t|j)sx?$': '@swc/jest',
    },
    transformIgnorePatterns: [
        // '/node_modules/',
        '\\.pnp\\.[^\\/]+$',
    ],
};

module.exports = config;
