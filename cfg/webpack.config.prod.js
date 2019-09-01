const webpack = require('webpack');
const path = require('path');

const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const { entry, rules } = require('./webpack.settings.js');

const plugins = [
	new CleanWebpackPlugin({
		root: path.join(__dirname, '..', 'public', 'assets', 'js'),
		exclude: ['vendor'],
		verbose: true,
	}),

	new webpack.optimize.OccurrenceOrderPlugin(),

	new webpack.DefinePlugin({
		'process.env.NODE_ENV': JSON.stringify('production'),
		'process.env.BABEL_ENV': JSON.stringify('production'),
	}),
];

module.exports = {
	mode: 'production',

	devtool: 'source-map',
	context: path.join(__dirname, '..', 'src', 'jsx'),

	resolve: {
		extensions: ['.jsx', '.js'],
		modules: [path.join(__dirname, '..', 'src', 'jsx'), 'node_modules'],
	},

	entry,

	performance: { hints: false },

	output: {
		path: path.join(__dirname, '..', 'public', 'assets', 'js'),
		publicPath: 'assets/js/',
		filename: '[name].js',
		chunkFilename: '[name].js',
	},

	optimization: {
		splitChunks: {
			cacheGroups: {
				vendor: {
					test: /[\\/]node_modules[\\/]/u,
					name: 'vendors',
					priority: -20,
					chunks: 'all',
				},
			},
		},
		minimizer: [
			new UglifyJsPlugin({
				uglifyOptions: {
					ecma: 5,
					warnings: false,
					mangle: true,
					keep_fnames: true,
					output: {
						comments: false,
					},
					compress: {
						dead_code: true,
						drop_console: true,
					},
				},
			}),
		],
	},

	plugins,

	module: {
		rules,
	},
};
