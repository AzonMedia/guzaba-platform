

/*const path = require('path')

function resolve(dir) {
    return path.join(__dirname, '..', dir)
}

module.exports = {
    entry: './src/main.js',
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'dist')
    },
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            '@/components/': resolve('src/GuzabaPlatform/Platform/components')
        }
    }
}*/

const path = require('path')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const fs = require('fs');

const aliases = require('./components_config/webpack.components.config.js').aliases
fs.writeFile('./components_config/webpack.components.runtime.json', JSON.stringify(aliases, null, ' '), function(){});

module.exports = {

    entry: ['./src/main.js'],
    output: {
        filename: '[name].bundle.js',
        path: __dirname + '/dist',
        publicPath: '/',
    },

    resolve: {
        extensions: ['*', '.webpack.js', '.web.js', '.ts', '.vue', '.js', '.scss'],
        alias: aliases,
        modules: [
            path.resolve(__dirname, 'app'),
            path.resolve(__dirname, 'node_modules')
        ]
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
                options: {
                    esModule: true
                }
            },
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, 'css-loader']
            },
            {
                test: /\.(png|jpg|gif|svg)$/,
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]?[hash]'
                }
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2)$/,
                use: 'url-loader?name=[name].[ext]'
            },
            {
                test: /\.scss$/,
                use: [{ loader: 'sass-loader' }]
            },
            {
                test: /\.(js)$/,
                exclude: /node_modules/,
                use: ['babel-loader', 'eslint-loader']
            }
        ]
    },
    devServer: {
        hot: true,
        port: 8080,
        historyApiFallback: true, // enable href locations
        inline: true
    },
    plugins: [
        new HtmlWebpackPlugin({
            title: 'Webpack 4 Starter',
            template: path.resolve(__dirname, 'src', 'index.html'),
            filename: 'index.html',
            inject: true,
            minify: {
                removeComments: true,
                collapseWhitespace: false
            }
        }),
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            //filename: isProd ? "[name]-[contenthash].css" : "[name].css"
        })
    ],
    /** @see https://webpack.js.org/configuration/watch/ */
    watchOptions: {
        //ignored: /node_modules/
        ignored: ['**/logs/**', 'node_modules/**']
    }
}
