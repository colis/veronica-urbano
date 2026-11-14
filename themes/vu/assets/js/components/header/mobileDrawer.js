import { containsClassName, isMobileDrawerOpen } from '../../helper/className';
import { checkKeyDown } from '../../helper/keyDown';
import { loadLazyImages } from '../../helper/lazyLoad';
import { closeOpenMenuItem, findOpenMenuItem } from './primaryNav';

let drawerElements;
let removeActiveTimeoutId = null;
const selectors = 'input, a, button';
const body = document.body;
const header = document.getElementById('masthead');
const headerLogo = header ? header.querySelector('.c-site-logo') : null;
const mobileButton = document.getElementById('primary-toggle-menu');
const drawer = header
	? header.querySelector('.js-primary-mobile-drawer')
	: null;
const headerTopMobile = header
	? header.querySelector(':scope > .c-site-header__top.mobile')
	: null;
export const mobileDrawerActiveClass = 'mobile-drawer-active';
const mobileDrawerClosedClass = 'mobile-drawer-closed';

/**
 * Mobile Drawer.
 *
 * Functionality to open and close the mobile drawer / navigation.
 *
 * @return {void}
 */
export const mobileDrawer = () => {
	if (!mobileButton || !drawer) {
		return;
	}

	// On hamburger button click.
	mobileButton.onclick = () => {
		if (mobileButton.getAttribute('aria-expanded') === 'false') {
			// Open mobile drawer.
			openMobileDrawer();
			return;
		}

		// Close mobile drawer.
		closeMobileDrawer();
	};

	// Ensure keydown event listener is only added once.
	if (!document._vuMobileDrawerKeydownListenerAdded) {
		document.addEventListener('keydown', function (event) {
			if (!isMobileDrawerOpen()) {
				return;
			}

			// If empty array, return early.
			if (!Array.isArray(drawerElements) || drawerElements.length === 0) {
				return;
			}

			// eslint-disable-next-line @wordpress/no-unused-vars-before-return
			const tabKey = checkKeyDown(event.key, 'Tab');

			const escKey = checkKeyDown(event.key, 'Escape');
			const shiftKey = event.shiftKey;

			const activeEl = document.activeElement;
			const firstEl = drawerElements[0];
			const lastEl = drawerElements[drawerElements.length - 1];

			// Close mobile drawer.
			if (escKey) {
				event.preventDefault();
				if (containsClassName(body, 'dialog-open')) {
					return;
				}
				closeMobileDrawer();
				return;
			}

			// Move focus to first element.
			if (!shiftKey && tabKey && lastEl === activeEl) {
				event.preventDefault();
				mobileButton.focus();
			}

			// Return focus to last element.
			if (shiftKey && tabKey && mobileButton === activeEl) {
				event.preventDefault();
				lastEl.focus();
			}

			// If there are no elements, don't move the focus.
			if (tabKey && firstEl === lastEl) {
				event.preventDefault();
			}
		});
		document._vuMobileDrawerKeydownListenerAdded = true;
	}
};

/**
 * Open the mobile drawer.
 */
const openMobileDrawer = () => {
	// Clear any pending timeout that would remove the active class
	if (removeActiveTimeoutId) {
		clearTimeout(removeActiveTimeoutId);
		removeActiveTimeoutId = null;
	}

	// Get all focusable elements in the mobile drawer.
	drawerElements = drawer.querySelectorAll(selectors);
	drawerElements = Array.prototype.slice.call(drawerElements);

	// Find any images with data-src attribute, and load them.
	const lazyImages = drawer.querySelectorAll('img[data-src]');
	loadLazyImages(lazyImages);

	// Set classes and element visibility.
	body.classList.add(mobileDrawerActiveClass);
	body.classList.remove(mobileDrawerClosedClass);
	mobileButton.setAttribute('aria-expanded', 'true');
	mobileButton.setAttribute(
		'aria-label',
		mobileButton.getAttribute('data-close-label')
	);
	drawer.classList.add('active');
	toggleHeaderSiblingVisibility(false);
};

/**
 * Close the mobile drawer.
 *
 * @return {void}
 */
const closeMobileDrawer = () => {
	// If the hamburger button is not expanded, bail early.
	if (mobileButton.getAttribute('aria-expanded') === 'false') {
		return;
	}

	// Close the open menu item.
	const openItem = findOpenMenuItem();
	if (openItem) {
		closeOpenMenuItem(openItem);
	}

	// Set classes and element visibility.
	document.documentElement.style.position = '';
	body.style.position = '';
	body.classList.add(mobileDrawerClosedClass);
	body.classList.remove(mobileDrawerActiveClass);
	mobileButton.setAttribute('aria-expanded', 'false');
	mobileButton.setAttribute(
		'aria-label',
		mobileButton.getAttribute('data-open-label')
	);
	toggleHeaderSiblingVisibility(true);

	// Focus the hamburger button.
	mobileButton.focus();

	// Remove mobile drawer active class after animation ends.
	removeActiveTimeoutId = setTimeout(() => {
		drawer.classList.remove('active');
		removeActiveTimeoutId = null;
	}, 500);
};

/**
 * Toggle visibility of Header & Mobile Drawer sibling elements (for ADA).
 *
 * @param {boolean} visible Whether to show or hide the elements.
 *
 * @return {void}
 */
const toggleHeaderSiblingVisibility = (visible) => {
	// Get Mobile Drawer sibling elements.
	let headerElements = Array.from(header.parentNode.children).filter(
		function (sibling) {
			return sibling !== header;
		}
	);

	// Add Header Top Bar sibling elements.
	if (headerTopMobile) {
		const topMobileElements = Array.from(
			headerTopMobile.querySelectorAll('a, button')
		);
		headerElements = headerElements.concat(topMobileElements);
	}

	// Add the Header Logo element.
	if (headerLogo) {
		headerElements.push(headerLogo);
	}

	// If there are no elements, return early.
	if (!headerElements.length) {
		return;
	}

	// Toggle visibility of the elements.
	headerElements.forEach(function (element) {
		// Show element if visible is true.
		if (visible) {
			element.removeAttribute('inert');
			element.setAttribute('aria-hidden', 'false'); // Fallback for browsers without inert.
			return;
		}

		// Hide element from the DOM.
		element.setAttribute('inert', '');
		element.setAttribute('aria-hidden', 'true'); // Fallback for browsers without inert.
	});
};
