/**
 * External dependencies
 */
import CopyWebpackPlugin from 'copy-webpack-plugin';
import _ from 'lodash';
import path from 'path';
import { fileURLToPath } from 'url';

/**
 * WordPress dependencies
 */
import defaultConfig from '@wordpress/scripts/config/webpack.config.js';

/**
 * Define paths
 */
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Themes.
const themePath = './themes/vu';

// Plugins.
const blocksPluginPath = './plugins/vu-blocks';
const corePluginPath = './plugins/vu-core';

/**
 * Configurations
 */
// JS rule overrides.
const jsRuleIndex = defaultConfig.module.rules.findIndex(
	(rule) => String(rule.test) === String(/\.[cm]?(j|t)sx?$/)
);

// Omit file extension when importing modules.
defaultConfig.module.rules[jsRuleIndex] = {
	...defaultConfig.module.rules[jsRuleIndex],
	resolve: {
		fullySpecified: false,
	},
};

// Parent Theme configuration.
const themeConfig = {
	...defaultConfig,
	entry: {
		frontend: [
			`${themePath}/assets/js/index.js`,
			`${themePath}/assets/css/frontend.css`,
		],
		admin: [
			`${themePath}/assets/js/admin.js`,
			`${themePath}/assets/css/admin.css`,
		],
	},
	output: {
		path: path.resolve(__dirname, `${themePath}/dist`),
	},
	plugins: [
		...defaultConfig.plugins,
		new CopyWebpackPlugin({
			patterns: [
				{
					from: `${themePath}/assets/lib`,
					to: 'lib',
					noErrorOnMissing: true,
				},
			],
		}),
	],
};

// Core Plugin configuration.
const corePluginConfig = {
	...defaultConfig,
	entry: {
		'admin-scripts': [`${corePluginPath}/assets/js/admin/admin.js`],
		'admin-styles': [`${corePluginPath}/assets/css/admin/admin-style.css`],
		'frontend-scripts': [
			`${corePluginPath}/assets/js/frontend/frontend.js`,
		],
	},
	output: {
		path: path.resolve(__dirname, `${corePluginPath}/dist/`),
	},
};

// Blocks Plugin configuration.
// CopyWebpackPlugin overrides.
process.env.WP_SOURCE_PATH = `${blocksPluginPath}/src`;
process.env.WP_COPY_PHP_FILES_TO_DIST = true;

const copyPluginIndex = defaultConfig.plugins.findIndex(
	(plugin) => plugin.constructor.name === 'CopyPlugin'
);

const blocksConfig = _.cloneDeep(defaultConfig);

// Change CopyWebpackPlugin context path.
blocksConfig.plugins[copyPluginIndex] = new CopyWebpackPlugin({
	patterns: [
		...defaultConfig.plugins[copyPluginIndex].patterns.map((pattern) => ({
			...pattern,
			context: `${blocksPluginPath}/src`,
		})),
	],
	options: defaultConfig.plugins[copyPluginIndex].options,
});

const entry = {
	...blocksConfig.entry(),
};

// Blocks Plugin Scripts configuration.
const blocksPluginConfig = {
	...blocksConfig,
	entry: {
		...entry,
		'_admin/icon-preview/index': [
			`${blocksPluginPath}/assets/js/admin/icon-preview/index.js`,
			`${blocksPluginPath}/assets/css/admin/icon-preview/index.css`,
		],
		'_extensions/index': `${blocksPluginPath}/src/_extensions/index.js`,
		'blocks-scripts': [`${blocksPluginPath}/assets/js/blocks-scripts.js`],
		'blocks-styles': [`${blocksPluginPath}/assets/css/blocks-styles.css`],
	},
	output: {
		path: path.resolve(__dirname, `${blocksPluginPath}/dist/`),
	},
};

// Process configurations.
export default [themeConfig, corePluginConfig, blocksPluginConfig];
