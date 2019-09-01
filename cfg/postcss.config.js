const settings = ctx => ({
	parser: 'postcss-scss',
	map: ctx.options.map,
	plugins: {
		'postcss-import': {},
		'postcss-at-rules-variables': {},
		'postcss-nested-ancestors': {},
		'postcss-custom-properties': {},
		'postcss-custom-selectors': {},
		'postcss-selector-not': {},
		'postcss-for': {},
		'postcss-each': {},
		'postcss-sassy-mixins': {},
		'postcss-conditionals': {},
		'postcss-advanced-variables': {},
		'postcss-quantity-queries': {},
		'postcss-hexrgba': {},
		'postcss-object-fit-images': {},
		'postcss-pxtorem': {
			rootValue: 16,
			selectorBlackList: ['body'],
		},
		'postcss-flexbugs-fixes': {},
		'postcss-size': {},
		'postcss-inline-svg': {
			path: 'src/svg',
			encode: code =>
				code
					.replace(/%/gu, '%25')
					.replace(/</gu, '%3C')
					.replace(/>/gu, '%3E')
					.replace(/&/gu, '%26')
					.replace(/#/gu, '%23'),
			removeFill: true,
		},
		'postcss-will-change': {},
		'postcss-nested': {},
		'postcss-calc': {
			warnWhenCannotResolve: false,
			mediaQueries: true,
		},
		'postcss-initial': {},
		'postcss-input-style': {},
		'postcss-easings': {},
		'postcss-mesh': {},
		autoprefixer: {},
		cssnano:
			ctx.env === 'production'
				? {
						filterPlugins: false,
						safe: true,
						mergeRules: false,
						discardComments: {
							removeAll: true,
						},
				  }
				: false,
	},
});

module.exports = settings;
