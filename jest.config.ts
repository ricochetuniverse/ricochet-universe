import type {Config} from 'jest';

const config: Config = {
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

export default config;
