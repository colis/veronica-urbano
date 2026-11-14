/**
 * Throttle function
 *
 * @param {Function} func  Function to throttle.
 * @param {number}   limit Limit in milliseconds.
 *
 * @return {Function} Throttled function.
 */
export const throttle = (func, limit = 200) => {
	let inThrottle = false;

	return function () {
		if (!inThrottle) {
			func.apply(this, arguments);
			inThrottle = true;

			setTimeout(function () {
				inThrottle = false;
			}, limit);
		}
	};
};
