'use strict';

const path = require('path');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

module.exports = {
    entry: {
        app: './resources/assets/js/app.js',
    },
    output: {
        path: path.resolve(__dirname, 'public/build/'),
        filename: '[name].[chunkhash].js',
        publicPath: '/build/',
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                options: {
                    cacheDirectory: true,
                },
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[hash].css',
                        },
                    },
                    'extract-loader',
                    'css-loader',
                    'postcss-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            implementation: require('dart-sass'),
                            fiber: require('fibers'),
                        },
                    },
                ],
            },
            {
                test: /\.(jpg|gif|png|svg|eot|ttf|woff|woff2)$/,
                loader: 'file-loader',
            },
        ],
    },
    resolve: {
        alias: {
            jquery: 'jquery/dist/jquery.min',
        },
    },
    plugins: [
        new CleanWebpackPlugin(['public/build/']),

        new ManifestPlugin({
            basePath: '/',
            fileName: path.resolve(__dirname, 'public/mix-manifest.json'),
        }),
    ],
    devtool: false,
};
