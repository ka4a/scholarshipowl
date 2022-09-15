const merge = require('webpack-merge');
const path = require('path');
const common = require('./webpack.common.js');

const port = 8081;

module.exports = merge(common, {
  output: {
    filename: '[name].js',
    publicPath: `http://localhost:${port}/js/`
  },
  watchOptions: {
    poll: true
  },
  // module: {
  //   rules: [{
  //       test: /\.(js|vue)$/,
  //       exclude: /node_modules/,
  //       loader: "eslint-loader",
  //       options: {
  //         fix: true,
  //       }
  //   }]
  // },
  devServer: {
    hot: true,
    inline: true,
    port,
    contentBase: path.join(__dirname, "public"),
    headers: {
      "Access-Control-Allow-Origin": "*"
    },
    proxy: {
      "*": "http://localhost:8080"
    }
  },
  stats: {
    moduleTrace: false,
  },
})