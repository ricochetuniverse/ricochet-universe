// @ts-check

'use strict';

/** @type {import('jest').Config} */
const config = {
    moduleNameMapper: {
        '^preact$': 'react',
        '^preact/hooks$': 'react',
    },
    testEnvironment: 'jsdom',
    transform: {
        '^.+\\.m?(t|j)sx?$': [
            '@swc/jest',

            // Merged with .swcrc
            {
                jsc: {
                    transform: {
                        react: {
                            // Need to test against React for now instead of
                            // Preact due to numerous issues/headache trying
                            // to set it up
                            importSource: 'react',
                        },
                    },
                },
                env: {},
            },
        ],
    },
};

module.exports = config;
