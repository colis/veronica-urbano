import { isMobileViewport } from '../helper/mediaQueries';

/**
 * Change the tag of an element.
 *
 * @param {element} element The element to change.
 * @param {string}  newTag  The new tag to use.
 *
 * @return {*} The new element.
 */
export const changeElementTag = (element, newTag) => {
	const newElement = document.createElement(newTag);
	newElement.innerHTML = element.innerHTML;
	element.parentNode.replaceChild(newElement, element);
	return newElement;
};

/**
 * Is an element visible el.checkVisibility() is not available in Safari yet.
 *
 * @param {element} element The element to check.
 *
 * @return {boolean} If element is visible or not.
 */
export const isVisible = (element) => {
	if (typeof element.checkVisibility === 'function') {
		return element.checkVisibility();
	}
	return element.offsetWidth > 0 || element.offsetHeight > 0;
};

/**
 * Remove DOM elements.
 *
 * @param {HTMLElement} element          The DOM element to remove elements from.
 * @param {string}      removalClassname The classname of the elements to remove.
 */
export const removeElements = (element, removalClassname) => {
	element.querySelectorAll(removalClassname).forEach((el) => {
		el.remove();
	});
};

/**
 * Get the height of the header including the admin bar if present.
 *
 * @return {number} The height in pixels.
 */
export const getHeaderHeight = () => {
	const headerDesktop = document.querySelector('.c-site-header');
	const headerMobile = document.querySelector('.js-header-content');
	const adminBar = document.querySelector('#wpadminbar');

	return isMobileViewport()
		? headerMobile.offsetHeight + (adminBar ? adminBar.offsetHeight : 0)
		: headerDesktop.offsetHeight + (adminBar ? adminBar.offsetHeight : 0);
};

/**
 * Determine if the target element (or its ancestor) is the last component in the page's content flow.
 *
 * @param {HTMLElement} targetEl - The target element to check.
 *
 * @return {boolean} - Returns true if the target element is the last component, false otherwise.
 */
export const lastComponentCheck = (targetEl) => {
	if (!targetEl) {
		return false;
	}
	const entryContent = targetEl.closest('.entry-content');
	const directChild = entryContent
		? Array.from(entryContent.children).find((child) =>
				child.contains(targetEl)
			)
		: null;
	return directChild && directChild === entryContent.lastElementChild;
};
