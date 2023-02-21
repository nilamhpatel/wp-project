const webpack = require( 'webpack' );
const inProduction = process.env.NODE_ENV === 'production';
const WebpackCleanPlugin = require( 'webpack-clean' );
const wpPot = require( 'wp-pot' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require( 'css-minimizer-webpack-plugin' );

const entry = {
	'js/ads-edit'                    : './src/js/ads-edit.js',
	'js/ads-group'                   : './src/js/ads-group.js',
	'js/ads-list'                    : './src/js/ads-list.js',
	'js/ads-new'                     : './src/js/ads-new.js',
	'js/blocks'                      : './src/js/blocks',
	'js/clipboard'                   : './src/js/clipboard.js',
	'js/custom-stats'                : './src/js/custom-stats.js',
	'js/group-widgets-admin'         : './src/js/group-widgets-admin.js',
	'js/settings'                    : './src/js/settings',
	'js/single-widgets-admin'        : './src/js/single-widgets-admin',
	'js/stats-dashboard-group-block' : './src/js/stats-dashboard-group-block.js',
	'js/tinymce.shortcodes'          : './src/js/tinymce.shortcodes.js',

	'css/about'                      : './src/css/about.scss',
	'css/add-ons'                    : './src/css/add-ons.scss',
	'css/admin-global'               : './src/css/admin-global.scss',
	'css/ads-edit'                   : './src/css/ads-edit.scss',
	'css/ads'                        : './src/css/ads.scss',
	'css/blocks'                     : './src/css/blocks.scss',
	'css/changelog'                  : './src/css/changelog.scss',
	'css/group-widgets-admin'        : './src/css/group-widgets-admin.css',
	'css/jquery-ui-custom'           : './src/css/jquery-ui-custom.css',
	'css/random-widgets-admin'       : './src/css/random-widgets-admin.css',
	'css/settings'                   : './src/css/settings.scss',
	'css/single-widgets-admin'       : './src/css/single-widgets-admin.css',
	'css/stats'                      : './src/css/stats.scss',
	'css/support'                    : './src/css/support.scss',
	'css/widget-default'             : './src/css/widget-default.scss',

	// Divi scripts live in their specific folder
	'../page-builders/divi/scripts/builder-bundle.min' : './page-builders/divi/includes/loader.js',
}

module.exports = {
	entry,
	module: {
		rules: [
			{
				test: /\.js$/,
				use: 'babel-loader',
			},
			{
				test: /\.(svg|woff(2)?|ttf|eot)$/,
				type: 'asset',
				parser: {
					dataUrlCondition: {
						maxSize: 10 * 1024, // 10kb
					},
				},
				generator: {
					filename: './fonts/[name][ext]',
				},
			},
			{
				test: /\.(png|jpg|gif)$/,
				type: 'asset',
				parser: {
					dataUrlCondition: {
						maxSize: 10 * 1024, // 10kb
					},
				},
				generator: {
					filename: './images/[name][ext]',
				},
			},
			{
				test: /\.(css|scss)$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
					},
					'sass-loader',
				],
			},
			{
				test: /\.php$/,
				use: wpPot({
					destFile: 'lang/adsanity.pot',
					domain: 'adsanity',
					package: 'AdSanity Core',
				}),
			},
		],

	},
	optimization: {
		minimizer: [
			`...`,
			new CssMinimizerPlugin(),
		],
	},
	plugins: [
		new MiniCssExtractPlugin({
			filename: "[name].css",
		}),
		new WebpackCleanPlugin(
			// Array of JS files in the CSS folder
			Object.keys( entry )
				.filter( ( key ) => key.startsWith( 'css' ) )
				.map( ( key ) => `dist/${key}.js` )
		),
		new MiniCssExtractPlugin(),
		new webpack.IgnorePlugin({
			resourceRegExp: /^\.\/locale$/,
			contextRegExp: /moment$/,
		}),
	],
	mode: inProduction ? 'production' : 'development',
	externals: {
		'react': 'React',
		'react-dom': 'ReactDOM',
	},
}
