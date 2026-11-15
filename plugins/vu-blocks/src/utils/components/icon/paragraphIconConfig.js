export const PARAGRAPH_ICON_STYLES = ['is-style-eyebrow'];

/**
 * Check whether a paragraph block style supports icons.
 *
 * @param {string|undefined|null} className Block class names.
 *
 * @return {boolean} True when the eyebrow style is active.
 */
export function isParagraphIconStyle(className) {
	if (!className) {
		return false;
	}

	const classes = className.split(/\s+/);

	return PARAGRAPH_ICON_STYLES.some((style) => classes.includes(style));
}
