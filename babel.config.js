'use strict';

module.exports = (api) => {
    const env = api.env();

    return {
        presets: [
            [
                '@babel/preset-env',
                {
                    modules: env === 'test' ? 'auto' : false,
                },
            ],
            [
                '@babel/preset-react',
                {
                    development: env === 'development',
                    runtime: 'automatic',
                    importSource: 'preact',
                    useBuiltIns: true,
                },
            ],
            '@babel/preset-typescript',
        ],
    };
};
