// @ts-check

/**
 * @import {Config} from 'jest'
 */

'use strict';

/** @type {Config} */
const config = {
    // extensionsToTreatAsEsm: ['.ts', '.tsx'],
    moduleNameMapper: {
        '^react$': 'preact/compat',
        '^react-dom$': 'preact/compat',
    },
    testEnvironment: 'jsdom',
    transform: {
        '^.+\\.m?(t|j)sx?$': '@swc/jest',
    },
    transformIgnorePatterns: [
        // '/node_modules/',
        '\\.pnp\\.[^\\/]+$',
    ],
};

module.exports = config;
