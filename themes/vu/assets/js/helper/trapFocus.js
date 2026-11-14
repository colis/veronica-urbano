/**
 * Trap the focus within a specific container, plus additional elements.
 *
 * @param {HTMLElement} container         The DOM element to find focusable elements in.
 * @param {Array}       additionalsBefore Additional DOM elements to include before container.
 * @param {Array}       additionalsAfter  Additional DOM elements to include after container.
 */
export const trapFocus = (
	container,
	additionalsBefore = [],
	additionalsAfter = []
) => {
	if (!container) {
		return;
	}

	let focusableElementsArray = [];
	const focusableElements = container.querySelectorAll(
		'a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])'
	);

	// Include additional DOM elements before container.
	if (additionalsBefore) {
		focusableElementsArray = Array.from(additionalsBefore);
	}

	// Include focusable DOM elements.
	focusableElementsArray = focusableElementsArray.concat(
		Array.from(focusableElements)
	);

	// Include additional DOM elements after container.
	if (additionalsAfter) {
		focusableElementsArray = focusableElementsArray.concat(
			Array.from(additionalsAfter)
		);
	}

	// If no focusable elements, return early.
	if (!focusableElementsArray.length) {
		return;
	}

	const firstFocusableEl = focusableElementsArray[0];
	const lastFocusableEl =
		focusableElementsArray[focusableElementsArray.length - 1];

	container.addEventListener('keydown', (e) => {
		if (e.key !== 'Tab') {
			return;
		}

		if (e.shiftKey && document.activeElement === firstFocusableEl) {
			e.preventDefault();
			lastFocusableEl.focus();
			return;
		}

		if (!e.shiftKey && document.activeElement === lastFocusableEl) {
			e.preventDefault();
			firstFocusableEl.focus();
		}
	});
};
