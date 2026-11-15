export const DEFAULT_ICON_SIZE = 24;
export const PARAGRAPH_DEFAULT_ICON_SIZE = 32;
export const MIN_ICON_SIZE = 16;
export const MAX_ICON_SIZE = 48;
export const ICON_SIZE_STEP = 2;
export const DEFAULT_ICON_GAP = 0;
export const ICON_GAP_OPTIONS = [0, 2, 4, 8, 12, 16];

/**
 * Normalize iconSize attribute into a positive integer.
 *
 * @param {string|number|undefined|null} iconSize    Icon size.
 * @param {number}                       defaultSize Fallback size in px.
 *
 * @return {number} Icon size in px.
 */
export function normalizeIconSize(iconSize, defaultSize = DEFAULT_ICON_SIZE) {
	const parsed = Number.parseInt(String(iconSize ?? ''), 10);
	return Number.isFinite(parsed) && parsed > 0 ? parsed : defaultSize;
}

/**
 * Normalize iconGap attribute into a supported gap value.
 *
 * @param {string|number|undefined|null} iconGap Icon gap.
 *
 * @return {number} Icon gap in px.
 */
export function normalizeIconGap(iconGap) {
	const parsed = Number.parseInt(String(iconGap ?? ''), 10);

	if (ICON_GAP_OPTIONS.includes(parsed)) {
		return parsed;
	}

	return DEFAULT_ICON_GAP;
}

/**
 * Build attributes when enabling a leading icon.
 *
 * @param {Object} attributes              Block attributes.
 * @param {Object} options                 Options.
 * @param {number} options.defaultIconGap  Default icon gap in px.
 * @param {number} options.defaultIconSize Default icon size in px.
 *
 * @return {Object} Attribute updates.
 */
export function getLeadingIconEnableAttributes(
	attributes,
	{
		defaultIconGap = DEFAULT_ICON_GAP,
		defaultIconSize = DEFAULT_ICON_SIZE,
	} = {}
) {
	const { iconGap, iconSize } = attributes;

	return {
		hasLeadingIcon: true,
		iconGap: iconGap || String(defaultIconGap),
		iconSize: iconSize || String(defaultIconSize),
	};
}

/**
 * Build attributes when enabling a trailing icon.
 *
 * @param {Object} attributes              Block attributes.
 * @param {Object} options                 Options.
 * @param {number} options.defaultIconGap  Default icon gap in px.
 * @param {number} options.defaultIconSize Default icon size in px.
 *
 * @return {Object} Attribute updates.
 */
export function getTrailingIconEnableAttributes(
	attributes,
	{
		defaultIconGap = DEFAULT_ICON_GAP,
		defaultIconSize = DEFAULT_ICON_SIZE,
	} = {}
) {
	const { iconGap, iconSize } = attributes;

	return {
		hasTrailingIcon: true,
		hasLeadingIcon: false,
		iconGap: iconGap || String(defaultIconGap),
		iconSize: iconSize || String(defaultIconSize),
	};
}
