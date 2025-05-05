'use strict';

import {type Configuration} from 'webpack';

const path = require('path');

const {CleanWebpackPlugin} = require('clean-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const {WebpackManifestPlugin} = require('webpack-manifest-plugin');

const config: Configuration = {
    entry: {
        app: './resources/js/app.ts',
    },
    output: {
        path: path.resolve(__dirname, 'public/build/'),
        filename: '[name].[chunkhash].js',
        publicPath: '/build/',
    },
    module: {
        rules: [
            {
                test: /\.[jt]sx?$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                resolve: {
                    extensions: ['.ts', '.tsx', '.js'],
                },
                options: {
                    cacheDirectory: true,
                },
            },
            {
                test: /\.(css|scss)$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader',
                ],
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: 'sass-loader',
                        options: {
                            // https://github.com/webpack-contrib/sass-loader/issues/763
                            sassOptions: {
                                outputStyle: 'expanded',
                            },
                        },
                    },
                ],
            },
            {
                test: /\.(jpg|gif|png|svg|eot|ttf|woff|woff2)$/,
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
    },
    plugins: [
        new CleanWebpackPlugin(),

        new WebpackManifestPlugin({
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

        new MiniCssExtractPlugin({
            filename: '[name].[contenthash].css',
        }),
    ],
    optimization: {
        minimizer: [
            new TerserPlugin({
                minify: TerserPlugin.esbuildMinify,
            }),
        ],
    },
    devtool: false,
};

module.exports = config;
