// @ts-check

'use strict';

const fs = require('node:fs');
const path = require('node:path');

const {rspack} = require('@rspack/core');
const browserslist = require('browserslist');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');
const {RspackManifestPlugin} = require('rspack-manifest-plugin');

function getSwcLoaderOptions() {
    /** @type {import('@rspack/core').SwcLoaderOptions} */
    const options = JSON.parse(
        fs.readFileSync(path.resolve(__dirname, '.swcrc'), 'utf-8')
    );

    // @ts-expect-error reading from .swcrc
    delete options['$schema'];
    return options;
}

/** @type {import('@rspack/cli').Configuration} */
const config = {
    entry: {
        app: './resources/js/app.ts',
    },
    output: {
        path: path.resolve(__dirname, 'public/build/'),
        filename: '[name].[contenthash].js',
        publicPath: '/build/',
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                exclude: /node_modules/,
                loader: 'builtin:swc-loader',
                resolve: {
                    extensions: ['.ts', '.tsx', '.js'],
                },
                options: getSwcLoaderOptions(),
            },
            {
                test: /\.(css|scss)$/,
                use: [rspack.CssExtractRspackPlugin.loader, 'css-loader'],
                type: 'javascript/auto',
            },
            {
                test: /\.scss$/,
                loader: 'sass-loader',
                options: {
                    api: 'modern-compiler',
                    implementation: require.resolve('sass-embedded'),
                    sassOptions: {
                        silenceDeprecations: [
                            'mixed-decls',
                            'color-functions',
                            'global-builtin',
                            'import',
                        ],
                    },
                },
            },
            {
                test: /\.(jpg|gif|png|svg|eot|ttf|woff|woff2)$/,
                type: 'asset/resource',
            },
            // https://getbootstrap.com/docs/5.3/getting-started/webpack/#extracting-svg-files
            {
                scheme: 'data',
                mimetype: 'image/svg+xml',
                generator: {
                    filename: '[contenthash].svg',
                },
                type: 'asset/resource',
            },
        ],
    },
    resolve: {
        alias: {
            react: 'preact/compat',
            'react-dom': 'preact/compat',

            [path.resolve(__dirname, './resources/js/helpers/TextDecoder.ts')]:
                path.resolve(
                    __dirname,
                    './resources/js/helpers/TextDecoder.browser.ts'
                ),

            ...(process.env.NODE_ENV === 'production'
                ? {
                      [path.resolve(
                          __dirname,
                          './resources/js/preact-debug.ts'
                      )]: false,
                  }
                : null),
        },
        symlinks: false,
    },
    plugins: [
        new CleanWebpackPlugin(),

        new RspackManifestPlugin({
            basePath: '/',
            fileName: path.resolve(__dirname, 'public/mix-manifest.json'),
        }),

        new MonacoWebpackPlugin({
            languages: [],
            features: [
                'bracketMatching',
                'clipboard',
                'contextmenu',
                'find',
                'folding',
                'gotoLine',
                'multicursor',
                'quickCommand',
            ],
        }),

        new rspack.CssExtractRspackPlugin({
            filename: '[name].[contenthash].css',
        }),
    ],
    optimization: {
        minimizer: [
            new rspack.SwcJsMinimizerRspackPlugin({}),

            new rspack.LightningCssMinimizerRspackPlugin({
                minimizerOptions: {
                    targets: browserslist.loadConfig({
                        path: path.resolve(__dirname),
                    }),
                },
            }),
        ],
    },
    devtool: false,
    ignoreWarnings: [
        // caused by monaco-editor
        /Critical dependency: require function is used in a way in which dependencies cannot be statically extracted/,
    ],
};

module.exports = config;
