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

        // Reactstrap issue
        // TypeError: Cannot assign to read only property 'name' of function
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
