'use strict';

module.exports = (api) => {
    const env = api.env();

    return {
        'presets': [
            [
                '@babel/preset-env',
                {
                    'modules': false,
                }
            ],
            [
                '@babel/preset-react',
                {
                    pragma: 'h',
                    useBuiltIns: true,
                },
            ],
        ],
        plugins: [
            '@babel/plugin-transform-runtime',
            '@babel/plugin-proposal-class-properties',
        ]
    }
};
