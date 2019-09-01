const webpack = require('webpack');
const path = require('path');

const { entry, rules } = require('./webpack.settings.js');
const CleanWebpackPlugin = require('clean-webpack-plugin');

const plugins = [
	new CleanWebpackPlugin(['js'], {
		root: path.join(__dirname, '..', 'public', 'assets'),
		exclude: ['vendor'],
		verbose: true,
	}),

	new webpack.DefinePlugin({
		'process.env.NODE_ENV': JSON.stringify('development'),
		'process.env.BABEL_ENV': JSON.stringify('development'),
	}),
];

module.exports = {
	mode: 'development',

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
		minimize: false,
	},

	plugins,

	module: {
		rules,
	},
};
