const path = require('path');
const webpack = require('webpack')
const merge = require('webpack-merge');
const common = require('./webpack.admin.common.js');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = merge(common, {
  mode: 'development',
  devtool: 'cheap-module-eval-source-map',
  plugins: [
    // new BundleAnalyzerPlugin(),
    new webpack.DefinePlugin({
      BASE_URL: JSON.stringify('https://sunrise.dev.scholarshipowl.com'),
      HOMEPAGE_URL: JSON.stringify('https://scholarship.app'),
      GOOGLE_CLIENT_ID: JSON.stringify('62990514107-1fqvo0irih2gk63mtl8ievb6fofbbjn0'),
    }),
  ],
  devServer: {
    hot: true,
    inline: true,
    contentBase: path.join(__dirname, "public"),
    port: 9000,
    proxy: {
      "!/**/*.{css,js,hot-update.json}'": { target: "http://sunrise.local", changeOrigin: true, secure: false }
    },
    headers: {
        "Access-Control-Allow-Origin": "*"
    }
  },
});
