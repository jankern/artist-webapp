// Webpack config to organize and build web frontend code 

// Require modulea and plugins
const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const CopyWebpackPlugin = require('copy-webpack-plugin');

// Set the npm runtime environment based on npm script call
// Setup the config object as function
module.exports = env => {

    // Config object
    let config = {};

    // Entry files to be bundled
    config.entry = {
        custom: './src/js/index.js',
        //static: './src/js/static.js'
    };

    // Definition and optimization of output chunks
    config.optimization = {
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /node_modules/,
                    chunks: 'initial',
                    name: 'vendor',
                    enforce: true
                },
            }
        }
    };

    // Assigning runtime environment to the mode variable
    config.mode = env !== undefined && env.production ? 'production' : 'development';
    console.log('NODE_ENV: ', config.mode);

    // Output definition for location and filename of the bundle files
    config.output = {
        path: path.resolve(__dirname, 'html/resources/dist'),
        filename: 'js/[name].bundle.js',
        chunkFilename: 'js/[name].bundle.js'
    };

    // Plugin array to assign custom plgins to the bundle process
    config.plugins = [];

    config.plugins.push(
      // Automated assignment if bundling files to an index.html -> the file can be opened in a browser for test porposes
      new HtmlWebpackPlugin({
        template: "./src/static/index.html",
        inject: "body",
        chunks: ["vendor", "custom"],
        chunksSortMode: function (b, a) {
          //alphabetical order to define the insert tags
          if (a.names[0] > b.names[0]) {
            return 1;
          }
          if (a.names[0] < b.names[0]) {
            return -1;
          }
          return 0;
        },
      }),

      new HtmlWebpackPlugin({
        template: "./src/static/reflexionen.html",
        inject: true,
        chunks: ["vendor", "custom"],
        filename: "./ausstellungen/reflexionen/index.html",
      }),

      new HtmlWebpackPlugin({
        template: "./src/static/werkschau.html",
        inject: true,
        chunks: ["vendor", "custom"],
        filename: "./ausstellungen/werkschau/index.html",
      }),

      // Write the css income stream to css bundle file(s)
      new MiniCssExtractPlugin({
        filename: "css/[name].bundle.css",
        chunkFilename: "css/[name].bundle.css",
      }),

      // Plugin to copy static web content (files without reference to js or scss files)
      new CopyWebpackPlugin([
        {
          from: path.join(__dirname, "./src/static"),
          ignore: ["*.html"],
        },
      ]),

      new webpack.ProvidePlugin({
        jQuery: "jquery",
        $: "jquery",
        jquery: "jquery",
        "window.$": "jquery",
        "window.jQuery": "jquery",
      })
    );

    // Module object to keep the info how resources are being loaded
    config.module = {
        rules: [
            {
                // Rules how to process JS code
                test: /\.m?js$/,
                exclude: /(node_modules|bower_components)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            }
            , {
                // Rules how to process CSS/SCSS code
                test: /\.(sa|sc|c)ss$/,
                use: [
                    config.mode !== 'production' ? 'style-loader' : MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader',
                    'sass-loader',
                ],
            }, {
                // Rules how to process images
                test: /\.(png|jpg|jpeg|gif)$/,
                loader: {
                    loader: 'file-loader',
                    options: {
                        name: 'img/[name].[ext]',
                        publicPath: '/'
                    }
                }
            }, {
                // Rules how to process font files
                test: /\.(svg|woff|woff2|ttf|eot)$/,
                loader: {
                    loader: 'file-loader',
                    options: {
                        name: 'font/[name].[ext]',
                        publicPath: '/'
                    }
                }
            }, {
                // Rules how to process pure html code called by js code
                test: /\.html$/,
                loader: 'raw-loader'
            }
        ]
    };

    // Dev tool - source maps will be used in test/dev mode 
    if (config.mode === 'development') {
        config.devtool = 'inline-source-map';
    }

    // Dev server - can be used while development ($ npm run build-dev)
    config.devServer = {
        contentBase: [path.join(__dirname, 'html/resources/dist'), path.join(__dirname, '')],
        compress: true,
        port: 9000,
        open: true,
    }

    // Return the config object
    return config;
}
