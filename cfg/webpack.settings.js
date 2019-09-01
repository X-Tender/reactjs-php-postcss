'use strict';
const fs = require('fs');

const entryWithHMR = entrys => {
	if (process.env.NODE_ENV === 'production') return entrys;
	return [entrys[0], 'react-hot-loader/patch'].concat(entrys.slice(1));
};

const settings = {
	devHost: 'http://HOST',
	devPath: '/PATH/public',
	entry: {
		app: entryWithHMR(['./App.jsx']),
	},
	rules: [
		{
			test: /\.jsx|js?$/u,
			exclude: /node_modules/u,
			use: [
				{
					loader: 'babel-loader',
				},
			],
		},
		{
			test: /\.jsx$/u,
			enforce: 'pre',
			loader: 'eslint-loader',
			exclude: /node_modules/u,
			options: {
				quiet: true,
			},
		},
	],
};

if (fs.existsSync('cfg/webpack.vars.local.js')) {
	const { devHost, devPath } = require('./webpack.vars.local.js');
	settings.devHost = devHost;
	settings.devPath = devPath;
}

module.exports = settings;
