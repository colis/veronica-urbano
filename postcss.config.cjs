/** @type {import('postcss-load-config').Config} */
const path = require('path');
const themeAssets = '/themes/vu/assets';

const config = {
	plugins: [
		require('autoprefixer'),
		require('cssnano')({
			preset: 'default',
		}),
		require('@csstools/postcss-global-data')({
			files: [
				path.join(
					__dirname,
					`${themeAssets}/css/01-tools/media/_breakpoints.css`
				),
			],
		}),
		require('@lehoczky/postcss-fluid')({
			min: '480px',
			max: '1440px',
		}),
		require('postcss-preset-env'),
		require('postcss-functions')({
			functions: {
				color: (name, preset = false) =>
					preset
						? `var(--wp--preset--color--${name})`
						: `var(--wp--custom--colors--${name})`,
				custom: (param) => `var(--wp--custom--${param})`,
				'font-size': (size) => `var(--wp--preset--font-size--${size})`,
				'font-weight': (size) =>
					`var(--wp--custom--typography--font-weight--${size})`,
				grid: (property) => `var(--wp--custom--grid--${property})`,
				heading: (level, prop) =>
					`var(--wp--custom--typography--heading-${level}--${prop})`,
				radius: (size) => `var(--wp--custom--border-radius--${size})`,
				rem: (px = false) =>
					px ? `${px.replace('px', '') / 16}rem` : '',
				spacing: (size) => `var(--wp--custom--spacings--${size})`,
				'spacing-step': (size) => `var(--wp--preset--spacing--${size})`,
				transition: (property) =>
					`var(--wp--custom--transitions--${property})`,
				typography: (property, preset = false) =>
					preset
						? `var(--wp--preset--${property})`
						: `var(--wp--custom--typography--${property})`,
			},
		}),
		require('postcss-mixins')({
			mixinsFiles: [
				path.join(
					__dirname,
					`${themeAssets}/css/01-tools/mixins/*.css`
				),
			],
		}),
		require('postcss-nested').default,
		require('postcss-assets')({
			loadPaths: [
				path.join(__dirname, `${themeAssets}/fonts`),
				path.join(__dirname, `${themeAssets}/images`),
				path.join(__dirname, `${themeAssets}/svg`),
			],
		}),
	],
};

module.exports = config;
