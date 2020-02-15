'use strict';

module.exports = (api) => {
    const env = api.env();

    const plugins = [
        '@babel/plugin-transform-runtime',
        '@babel/plugin-proposal-class-properties',
        '@babel/plugin-syntax-dynamic-import',
    ];

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
                    modules: false,
                },
            ],
            [
                '@babel/preset-react',
                {
                    pragma: 'h',
                    pragmaFrag: 'Fragment',
                    useBuiltIns: true,
                },
            ],
            '@babel/preset-flow',
        ],
        plugins: plugins,
    };
};
