import { mobileDrawerActiveClass } from '../components/header/mobileDrawer';
/**
 * Check if an element contains a classname.
 *
 * @param {HTMLElement} element   The element to check. Defaults to the body.
 * @param {string}      className The classname to check for.
 *
 * @return {boolean} True if the element contains the classname, false otherwise.
 */
export const containsClassName = (element, className) => {
	// eslint-disable-next-line no-undef
	if (!element || !(element instanceof HTMLElement)) {
		throw new TypeError('The provided element is not a valid HTMLElement.');
	}

	if (!className || typeof className !== 'string') {
		return false;
	}

	return element?.classList.contains(className);
};

/**
 * Check if the mobile drawer is open.
 *
 * @return {boolean} True if the mobile drawer is open, false otherwise.
 */
export const isMobileDrawerOpen = () => {
	return containsClassName(document.body, mobileDrawerActiveClass);
};
