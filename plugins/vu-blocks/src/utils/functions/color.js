const CUSTOM_COLOR_VAR_PREFIX = '--wp--custom--colors--';

/**
 * Resolve a palette slug to a theme custom CSS variable value.
 *
 * Hyphens in the slug become double hyphens in the variable path
 * (e.g. brand-red → var(--wp--custom--colors--brand--red)).
 *
 * @param {string} slug Palette slug.
 *
 * @return {string|undefined} CSS var() value.
 */
export function colorSlugToCustomCSSValue(slug) {
	if (!slug || typeof slug !== 'string') {
		return undefined;
	}

	const trimmed = slug.trim();

	if (trimmed === '') {
		return undefined;
	}

	const varName = `${CUSTOM_COLOR_VAR_PREFIX}${trimmed.split('-').join('--')}`;

	return `var(${varName})`;
}

/**
 * Resolve a stored color attribute (slug, hex, or CSS var) to a CSS value.
 *
 * @param {string} value Stored attribute value.
 *
 * @return {string|undefined} CSS value for custom properties.
 */
export function resolveStoredColorCSSValue(value) {
	if (!value || typeof value !== 'string') {
		return undefined;
	}

	const trimmed = value.trim();

	if (trimmed === '') {
		return undefined;
	}

	if (trimmed.startsWith('#') || trimmed.startsWith('var(')) {
		return trimmed;
	}

	if (trimmed.startsWith('var:preset|color|')) {
		return colorSlugToCustomCSSValue(
			trimmed.slice('var:preset|color|'.length)
		);
	}

	if (/^[a-z0-9-]+$/i.test(trimmed)) {
		return colorSlugToCustomCSSValue(trimmed);
	}

	return trimmed;
}

/**
 * Simple luminance check to determine if a hex color is "light".
 *
 * @param {string} hex Hex color string (e.g., "#FFFFFF").
 *
 * @return {boolean} True if the color is light, false otherwise.
 */
export function isLightColor(hex) {
	const c = hex.replace('#', '');
	const r = parseInt(c.substring(0, 2), 16);
	const g = parseInt(c.substring(2, 4), 16);
	const b = parseInt(c.substring(4, 6), 16);
	const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
	return luminance > 0.5;
}
