'use strict';

const path = require('path');

const CleanWebpackPlugin = require('clean-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const MonacoWebpackPlugin = require('monaco-editor-webpack-plugin');

module.exports = {
    entry: {
        app: './resources/js/app.js',
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
                test: /\.css$/,
                use: ['style-loader', 'postcss-loader'],
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
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
            react: 'preact/compat',
            'react-dom': 'preact/compat',
        },
    },
    plugins: [
        new CleanWebpackPlugin(),

        new ManifestPlugin({
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
    devtool: false,
};
