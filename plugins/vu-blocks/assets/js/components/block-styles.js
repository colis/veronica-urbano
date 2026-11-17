import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

/**
 * Block style settings.
 */
domReady(() => {
	/**
	 * Remove styles.
	 */
	/* Buttons */
	unregisterBlockStyle('core/button', 'fill');
	unregisterBlockStyle('core/button', 'outline');

	/* Quote */
	unregisterBlockStyle('core/quote', 'plain');

	/* Separator */
	unregisterBlockStyle('core/separator', 'dots');
	unregisterBlockStyle('core/separator', 'wide');

	/* Table */
	unregisterBlockStyle('core/table', 'stripes');

	/**
	 * Add styles.
	 */
	/* Buttons */
	registerBlockStyle('core/button', {
		name: 'fill',
		label: 'Primary',
		isDefault: true,
	});
	registerBlockStyle('core/button', {
		name: 'outline',
		label: 'Secondary',
	});
	registerBlockStyle('core/button', {
		name: 'tertiary',
		label: 'Tertiary',
	});
	registerBlockStyle('core/button', {
		name: 'text',
		label: 'Text',
	});

	/* Box Shadow */
	registerBlockStyle('core/group', {
		name: 'box-shadow',
		label: 'Box Shadow',
	});

	/* List */
	registerBlockStyle('core/list', {
		name: 'featured',
		label: 'Featured',
	});
	registerBlockStyle('core/list', {
		name: 'featured-years',
		label: 'Featured Years',
	});

	/* Media & Text */
	registerBlockStyle('core/media-text', {
		name: 'classic-callout',
		label: 'Classic Callout',
	});
	registerBlockStyle('core/media-text', {
		name: 'profile-card',
		label: 'Profile Card',
	});

	/* Paragraph */
	registerBlockStyle('core/paragraph', {
		name: 'eyebrow',
		label: 'Eyebrow',
	});

	/* Paragraph */
	registerBlockStyle('core/paragraph', {
		name: 'pill',
		label: 'Pill',
	});

	/* Separator */
	registerBlockStyle('core/separator', {
		name: 'three-dots',
		label: 'Three Dots',
	});
});
