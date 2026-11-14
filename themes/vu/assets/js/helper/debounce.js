/**
 * Debounce function
 *
 * @param {Function} func  Function to debounce.
 * @param {number}   delay Delay in milliseconds.
 *
 * @return {Function} Debounced function.
 */
export const debounce = (func, delay = 300) => {
	let timer;

	return function (event) {
		if (timer) {
			clearTimeout(timer);
		}
		timer = setTimeout(func, delay, event);
	};
};
