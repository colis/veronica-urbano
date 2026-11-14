/**
 * Check the keydown event.
 *
 * @param {string} eventKey The pressed key.
 * @param {string} key      The key to check against.
 *
 * @return {boolean} True if the key is pressed. Default is false.
 */
export const checkKeyDown = (eventKey, key) => {
	if (!eventKey) {
		return false;
	}

	if (eventKey === key) {
		return true;
	}

	return false;
};
