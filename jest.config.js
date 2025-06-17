// @ts-check

/** @type {import('jest').Config} */
export default {
    moduleNameMapper: {
        '^react$': 'preact/compat',
        '^react/jsx-runtime$': 'preact/jsx-runtime',
        '^react-dom$': 'preact/compat',
    },
    // https://mswjs.io/docs/faq/#requestresponsetextencoder-is-not-defined-jest
    testEnvironment: 'jest-fixed-jsdom',
    transformIgnorePatterns: [
        // should be blank to transform `preact/jsx-runtime/dist/jsxRuntime.module.js`
    ],
    transform: {
        '^.+\\.m?(t|j)sx?$': '@swc/jest',
    },
};
