/* global vuCore */
import { getIconSvgString, svgToDataUri } from '../../functions/icons';
import {
	normalizeIconGap,
	normalizeIconSize,
	PARAGRAPH_DEFAULT_ICON_SIZE,
} from './iconConfig';
import { isParagraphIconStyle } from './paragraphIconConfig';

/**
 * Check whether icon output should be active.
 *
 * @param {Object}  attributes            Block attributes.
 * @param {Object}  options               Options.
 * @param {boolean} options.allowTrailing Allow trailing icon position.
 *
 * @return {boolean} True when an icon should render.
 */
function isIconActive(attributes, { allowTrailing = false } = {}) {
	const { hasLeadingIcon, hasTrailingIcon, icon } = attributes;

	if (!icon) {
		return false;
	}

	if (hasLeadingIcon) {
		return true;
	}

	return allowTrailing && hasTrailingIcon;
}

/**
 * Resolve icon position from block attributes.
 *
 * @param {Object} attributes Block attributes.
 *
 * @return {string|null} Icon position.
 */
function getIconPosition(attributes) {
	const { hasLeadingIcon, hasTrailingIcon } = attributes;

	if (hasLeadingIcon) {
		return 'leading';
	}

	if (hasTrailingIcon) {
		return 'trailing';
	}

	return null;
}

/**
 * Generate icon class names.
 *
 * @param {Object}  attributes            Block attributes.
 * @param {string}  className             Existing class names.
 * @param {Object}  options               Options.
 * @param {boolean} options.allowTrailing Allow trailing icon position.
 *
 * @return {string} Space-separated class names.
 */
export function generateIconClassNames(
	attributes,
	className,
	{ allowTrailing = false } = {}
) {
	const classNames = [className];
	const { icon } = attributes;

	if (!isIconActive(attributes, { allowTrailing })) {
		return classNames.join(' ');
	}

	const iconPosition = getIconPosition(attributes);

	classNames.push('has-icon');
	classNames.push(`has-icon-${iconPosition}`);
	classNames.push(`has-icon-${icon}`);

	return classNames.join(' ');
}

/**
 * Generate icon inline styles.
 *
 * @param {Object}  attributes              Block attributes.
 * @param {Object}  inlineStyles            Existing inline styles.
 * @param {Object}  options                 Options.
 * @param {boolean} options.allowTrailing   Allow trailing icon position.
 * @param {number}  options.defaultIconSize Default icon size in px.
 *
 * @return {Object} Inline style object with icon CSS variables.
 */
export function generateIconInlineStyles(
	attributes,
	inlineStyles,
	{ allowTrailing = false, defaultIconSize = undefined } = {}
) {
	const { icon, iconGap, iconSize } = attributes;

	if (!isIconActive(attributes, { allowTrailing })) {
		return inlineStyles;
	}

	const svgString = getIconSvgString(vuCore?.vuIcons?.[icon]);
	const dataUri = svgToDataUri(svgString);

	if (!dataUri) {
		return inlineStyles;
	}

	return {
		...inlineStyles,
		'--icon-gap': `${normalizeIconGap(iconGap)}px`,
		'--icon-size': `${normalizeIconSize(iconSize, defaultIconSize)}px`,
		'--icon-url': `url("${dataUri}")`,
	};
}

/**
 * Generate button icon class names.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} className  Existing class names.
 *
 * @return {string} Space-separated class names.
 */
export function generateButtonIconClassNames(attributes, className) {
	return generateIconClassNames(attributes, className, {
		allowTrailing: true,
	});
}

/**
 * Generate button icon inline styles.
 *
 * @param {Object} attributes   Block attributes.
 * @param {Object} inlineStyles Existing inline styles.
 *
 * @return {Object} Inline style object with icon CSS variables.
 */
export function generateButtonIconInlineStyles(attributes, inlineStyles) {
	return generateIconInlineStyles(attributes, inlineStyles, {
		allowTrailing: true,
	});
}

/**
 * Generate paragraph icon class names.
 *
 * @param {Object} attributes Block attributes.
 * @param {string} className  Existing class names.
 *
 * @return {string} Space-separated class names.
 */
export function generateParagraphIconClassNames(attributes, className) {
	if (!isParagraphIconStyle(attributes.className)) {
		return className;
	}

	return generateIconClassNames(attributes, className);
}

/**
 * Generate paragraph icon inline styles.
 *
 * @param {Object} attributes   Block attributes.
 * @param {Object} inlineStyles Existing inline styles.
 *
 * @return {Object} Inline style object with icon CSS variables.
 */
export function generateParagraphIconInlineStyles(attributes, inlineStyles) {
	if (!isParagraphIconStyle(attributes.className)) {
		return inlineStyles;
	}

	return generateIconInlineStyles(attributes, inlineStyles, {
		defaultIconSize: PARAGRAPH_DEFAULT_ICON_SIZE,
	});
}
