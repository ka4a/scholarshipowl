const path = require('path');
const webpack = require('webpack')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer');
const WebpackMd5Hash = require('webpack-md5-hash');
const AssetsPlugin = require('assets-webpack-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');

module.exports = {
  entry: {
    'app': ['app.js', path.resolve('./resources/assets/sass/style-gide/_layout.scss')],
    'external-components': 'external-components.js',
    'account-mobile': 'account-mobile.js',
  },
  output: {
    path: path.resolve(__dirname, 'public/js'),
    chunkFilename: '[name]-[hash]-[chunkhash].js',
  },
  plugins: [
    // new BundleAnalyzerPlugin(),
    new webpack.DefinePlugin({
      'process.env': {
        'NODE_ENV': JSON.stringify('production')
      }
    }),
    new WebpackMd5Hash(),

    // Creates a 'webpack-assets.json' file with all of the
    // generated chunk names so you can reference them
    new AssetsPlugin(),
    new webpack.HotModuleReplacementPlugin(),
    new VueLoaderPlugin()
  ],
  resolve: {
    modules: [
      path.resolve('./resources/assets/js'),
      'node_modules'
    ]
  },
  module: {
    rules: [
    {
      test: /\.vue$/,
      loader: 'vue-loader',
    },
    {
     test: /\.s?css$/,
     use: [
      'vue-style-loader',
      {
        loader: 'css-loader',
        options: {
          importLoader: 1,
        }
      },
      'postcss-loader',
      {
        loader: 'sass-loader',
        options: {
          includePaths: [
            path.resolve(__dirname, 'resources/assets/sass'),
            path.resolve(__dirname, 'public/assets/scss/'),
            './node_modules',],
          data: `@import 'breakpoint-sass/stylesheets/breakpoint';
                 @import 'style-gide/breakpoints';
                 @import 'style-gide/assets';
                 @import 'style-gide/index';`
        }
      }
     ],
    },
    {
      test: /\.(svg|gif|png|jpg)$/,
      loader: 'url-loader',
      options: {
        limit: 8192
      }
    },
    {
      test: /\.(woff|woff2|eot|ttf|otf)$/,
      loader: 'file-loader'
    },
    {
      test: /\.js$/,
      loader: 'buble-loader?objectAssign=Object.assign',
    },{
      test: /\.json$/,
      loader: 'json-loader'
    }]
  },

  devtool: 'cheap-module-source-map',
};
