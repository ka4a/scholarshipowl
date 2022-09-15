'use strict';

const path = require('path');
const webpack = require('webpack')
const { VueLoaderPlugin } = require('vue-loader')

const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const devMode = process.env.NODE_ENV !== 'production'

module.exports = {
  entry: {
    admin: "./resources/admin/main.js",
  },
  output: {
    path: path.resolve(__dirname, 'public/js'),
    filename: "[name].js",
    chunkFilename: 'admin-[name]-[chunkhash].js',
    publicPath: '/js/'
  },
  plugins: [
    // new webpack.DefinePlugin({
    //   'process.env': {
    //     'NODE_ENV': JSON.stringify('production')
    //   }
    // }),

    new webpack.HotModuleReplacementPlugin(),
    new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    new VueLoaderPlugin()
  ],
  resolve: {
    extensions: [".webpack.js", ".web.js", ".js", ".vue", ".json"],
    modules: [
      path.resolve('./resources/admin'),
      'node_modules'
    ],
    alias: {
      'masonry': 'masonry-layout',
      'isotope': 'isotope-layout',
      // 'components': path.resolve(__dirname, 'resources/admin/components'),
      // 'assets': path.resolve(__dirname, 'resources/admin/assets'),
      // 'lib': path.resolve(__dirname, 'resources/admin/lib'),
      // 'icon': path.resolve(__dirname, 'resources/admin/icon'),
    }
  },
  module: {
    rules: [{
      test: /\.vue$/,
      loader: 'vue-loader',
    },
    {
      test: /\.(gif|png|jpe?g)$/i,
      use: [
        'file-loader',
        {
          loader: 'image-webpack-loader',
          options: {
            bypassOnDebug: true,
          },
        },
      ],
    },
    {
      test: /\.css$/i,
      use: [
        devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
        'css-loader',
      ]
    },
    {
      test: /\.s[a|c]ss$/,
      use: [
        devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
        'css-loader',
        'sass-loader',
      ]
    },
    {
      test: /\.(woff|woff2|eot|ttf|otf|svg)$/,
      loader: 'file-loader'
    },
    {
      test: /\.js$/,
      loader: 'babel-loader',
      exclude: /node_modules/
    }]
  },
};
