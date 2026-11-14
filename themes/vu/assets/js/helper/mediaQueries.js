/**
 * Get a media query breakpoint number value from a CSS variable set in theme.json file.
 *
 * @param {string} breakpoint The breakpoint to retrieve.
 * @param {string} type       The type of breakpoint ("min" or "max").
 *
 * @return {string} The breakpoint value.
 */
export const getMediaQueryBreakpointValue = (breakpoint, type = 'min') => {
	let breakpointValue = window
		.getComputedStyle(document.body)
		.getPropertyValue(`--wp--custom--breakpoints--${breakpoint}`);

	if (type === 'max') {
		breakpointValue = Number(breakpointValue) - 1;
	}

	return Number(breakpointValue);
};

/**
 * Get a media query breakpoint from a CSS variable set in theme.json file.
 *
 * @param {string} breakpoint The breakpoint to retrieve.
 * @param {string} type       The type of breakpoint ("min" or "max").
 *
 * @return {string} The breakpoint value.
 */
export const getMediaQueryBreakpoint = (breakpoint, type = 'min') => {
	let breakpointValue = window
		.getComputedStyle(document.body)
		.getPropertyValue(`--wp--custom--breakpoints--${breakpoint}`);

	if (type === 'max') {
		breakpointValue = Number(breakpointValue) - 1;
	}

	return `(${type}-width: ${String(breakpointValue)}px)`;
};

/**
 * Check if the window viewport is a mobile device.
 *
 * @return {boolean} True if the device is a mobile device, false otherwise.
 */
export const isMobileViewport = () => {
	return getMediaQueryBreakpointValue('lg', 'max') >= window.innerWidth;
};
