const path = require('path');
const webpack = require('webpack')
const merge = require('webpack-merge');
const common = require('./webpack.admin.common.js');

// const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

module.exports = merge(common, {
  mode: 'production',
  devtool: 'source-map',
  stats: 'errors-only',
  output: {
    filename: "[name]-[hash:8].js",
  },
  optimization: {
    minimizer: [
      // new UglifyJsPlugin({
      //   cache: true,
      //   parallel: true,
      //   sourceMap: true // set to true if you want JS source maps
      // }),
      new OptimizeCSSAssetsPlugin({})
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      BASE_URL: JSON.stringify('https://app.scholarship.app'),
      HOMEPAGE_URL: JSON.stringify('https://scholarship.app'),
      GOOGLE_CLIENT_ID: JSON.stringify('62990514107-hdno8lp3hsfm7aok72lmdcn53jud6jg7'),
    }),
    new HtmlWebpackPlugin({
      title: 'Laravel blade view',
      template: path.resolve('./resources/views/templates/admin.blade.php'),
      filename: path.resolve('./resources/views/layout/admin.blade.php'),
    }),
    new MiniCssExtractPlugin({
      filename: "[name]-[hash].css",
    })
  ]
});
