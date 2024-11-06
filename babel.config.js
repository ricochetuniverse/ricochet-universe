'use strict';

module.exports = (api) => {
    const env = api.env();

    const plugins = ['babel-plugin-syntax-hermes-parser'];

    if (env === 'production') {
        plugins.push([
            'transform-imports',
            {
                reactstrap: {
                    transform: 'reactstrap/lib/${member}',
                    preventFullImport: true,
                },
            },
        ]);
    }

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
            '@babel/preset-flow',
        ],
        plugins: plugins,
    };
};
