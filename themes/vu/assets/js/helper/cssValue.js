/**
 * Return an element's CSS value
 *
 * @param {HTMLElement} element  The DOM element.
 * @param {string}      property The CSS property to return.
 * @param {string}      type     The return type.
 *
 * @return {*} The CSS value.
 */
export const cssValue = (element, property, type = 'string') => {
	let value = window.getComputedStyle(element).getPropertyValue(property);

	// If the return type is an integer, remove all letters.
	if (type === 'int' || type === 'integer' || type === 'number') {
		value = Number(value.replace(/[a-z]/g, ''));
	}

	return value;
};
