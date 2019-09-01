'use strict';

const webpack = require('webpack');

const argv = require('yargs').argv;
let openOption = 'external';
if (argv.oo) {
	openOption = argv.oo;
}
if (argv.h) {
	openOption = false;
}

const browserSync = require('browser-sync');
const webpackDevMiddleware = require('webpack-dev-middleware');
const webpackHotMiddleware = require('webpack-hot-middleware');

const webpackConfig = require('./webpack.config');
const bundler = webpack(webpackConfig);

const webpackSettings = require('./webpack.settings');
const { devHost, devPath } = webpackSettings;

const crossOrginHeaderMiddlware = function crossOrginHeaderMiddlware(req, res, next) {
	res.setHeader('Access-Control-Allow-Origin', '*');
	next();
};

const webpackDev = webpackDevMiddleware(bundler, {
	publicPath: webpackConfig.output.publicPath,
	stats: {
		colors: true,
		hash: false,
		version: false,
		timings: false,
		assets: false,
		chunks: false,
		modules: false,
		reasons: false,
		children: false,
		source: false,
		errors: true,
		errorDetails: true,
		warnings: true,
		publicPath: false,
	},
	quiet: false,
});

const webpackHot = webpackHotMiddleware(bundler);

browserSync({
	open: openOption,
	logLevel: 'info',
	ghostMode: {
		clicks: false,
		forms: false,
		scroll: false,
	},
	notify: {
		styles: {
			top: 'auto',
			bottom: '0',
			opacity: 0.5,
			borderBottomLeftRadius: 0,
			borderTopLeftRadius: '5px',
			padding: '10px',
			fontSize: '10px',
		},
	},
	proxy: `${devHost}${devPath}`,
	host: 'localhost',
	middleware: [crossOrginHeaderMiddlware, webpackDev, webpackHot],
	files: ['public/assets/css/*.css'],
});
