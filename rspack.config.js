import {readFileSync} from 'node:fs';
import {dirname, join, resolve} from 'node:path';
import process from 'node:process';
import {fileURLToPath} from 'node:url';

import {rspack} from '@rspack/core';
import browserslist from 'browserslist';
import {CleanWebpackPlugin} from 'clean-webpack-plugin';
import MonacoWebpackPlugin from 'monaco-editor-webpack-plugin';
import {RspackManifestPlugin} from 'rspack-manifest-plugin';

// https://stackoverflow.com/questions/64383909/dirname-is-not-defined-error-in-node-js-14-version/64383997#64383997
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const unpackerVersion = JSON.parse(readFileSync('package.json', 'utf-8'))[
    'dependencies'
]['@ricochetuniverse/nuvelocity-unpacker'];

function getSwcLoaderOptions() {
    /** @type {import('@rspack/core').SwcLoaderOptions} */
    const options = JSON.parse(
        readFileSync(resolve(__dirname, '.swcrc'), 'utf-8')
    );

    // @ts-expect-error reading from .swcrc
    delete options['$schema'];
    return options;
}

/** @type {import('@rspack/cli').Configuration} */
export default {
    entry: {
        app: './resources/js/app.ts',
    },
    output: {
        path: join(__dirname, 'public/build/'),
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
                    sassOptions: {
                        silenceDeprecations: [
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

            [resolve(__dirname, './resources/js/helpers/TextDecoder.ts')]:
                resolve(
                    __dirname,
                    './resources/js/helpers/TextDecoder.browser.ts'
                ),

            ...(process.env.NODE_ENV === 'production'
                ? {
                      [resolve(__dirname, './resources/js/preact-debug.ts')]:
                          false,
                  }
                : null),
        },
        symlinks: false,
    },
    plugins: [
        new CleanWebpackPlugin(),

        new RspackManifestPlugin({
            basePath: '/',
            fileName: resolve(__dirname, 'public/mix-manifest.json'),
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

        new rspack.CopyRspackPlugin({
            patterns: [
                {
                    from: 'node_modules/@ricochetuniverse/nuvelocity-unpacker/dotnet/_framework/',
                    to: join(
                        __dirname,
                        'public/build/nuvelocity-unpacker/',
                        unpackerVersion,
                        '/[name][ext]'
                    ),
                },
            ],
        }),
    ],
    optimization: {
        minimizer: [
            new rspack.SwcJsMinimizerRspackPlugin({}),

            new rspack.LightningCssMinimizerRspackPlugin({
                minimizerOptions: {
                    targets: browserslist.loadConfig({
                        path: resolve(__dirname),
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
