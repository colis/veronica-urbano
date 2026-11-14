import wordpress from '@wordpress/eslint-plugin';

export default [
	...wordpress.configs.recommended,
	{
		ignores: [
			'**/.vscode',
			'**/dist',
			'plugins/*',
			'!plugins/vu-*',
			'themes/*',
			'!themes/vu*',
			'**/vendor',
			'**/webpack.config.js',
		],
	},
	{
		settings: {
			'import/resolver': {
				webpack: {
					config: {
						resolve: {
							extensions: ['.js', '.jsx'],
						},
					},
				},
			},
		},
		rules: {
			'@wordpress/no-global-event-listener': 0,
			'@wordpress/no-global-active-element': 0,
			camelcase: 1,
			'jsdoc/reject-function-type': 0,
			'jsdoc/reject-any-type': 0,
			'no-console': [
				'error',
				{
					allow: ['warn', 'error'],
				},
			],
			'prettier/prettier': [
				'error',
				{
					endOfLine: 'auto',
				},
			],
		},
	},
];
