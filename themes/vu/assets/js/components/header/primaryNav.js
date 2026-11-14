import { containsClassName, isMobileDrawerOpen } from '../../helper/className';
import { checkKeyDown } from '../../helper/keyDown';
import { loadLazyImages } from '../../helper/lazyLoad';
import { trapFocus } from '../../helper/trapFocus';

/**
 * Site Primary Navigation (desktop & mobile).
 *
 * @package
 * @since 1.0.0
 */

// Define class name constants.
const activeClass = 'active';
const subMenuOpenClass = 'sub-menu-open';
const subMenuClosingClass = 'sub-menu-closing';
const submenuOverlayClassName = 'js-primary-nav-submenu-overlay';

// Define element constants.
const siteNavigation = document.getElementById('header-primary-menu');
const menuItemToggleButtons = siteNavigation?.querySelectorAll(
	'.js-menu-item-toggle'
);
const backButton =
	document.querySelector('.js-primary-mobile-drawer-back') ?? '';
const mobileButton = document.getElementById('primary-toggle-menu');

/**
 * Primary navigation functionality.
 *
 * @return {void}
 */
export const primaryNav = () => {
	if (!siteNavigation) {
		return;
	}

	// Ensure event listeners are only added once.
	if (!document._vuPrimaryNavListenersAdded) {
		/**
		 * Close menu item on Escape key press.
		 */
		document.addEventListener('keydown', function (event) {
			const escKey = checkKeyDown(event.key, 'Escape');
			if (escKey) {
				closeOpenMenuItem();
			}
		});

		/**
		 * Close menu item on overlay click.
		 */
		siteNavigation.addEventListener('click', (e) => {
			if (e.target.className.includes(submenuOverlayClassName)) {
				closeOpenMenuItem();
			}
		});

		/**
		 * Close menu item on mobile back button click.
		 */
		if (backButton) {
			backButton.addEventListener('click', () => {
				closeOpenMenuItem();
			});
		}

		/**
		 * Toggle menu item on click.
		 */
		if (menuItemToggleButtons.length) {
			for (const button of menuItemToggleButtons) {
				button.addEventListener('click', (event) => {
					toggleMenuItem(event.currentTarget);
				});
			}
		}

		/**
		 * Lazy load sub menu images on interaction.
		 */
		siteNavigation.addEventListener('mouseover', () => {
			const lazyImages = siteNavigation.querySelectorAll('img[data-src]');
			if (!lazyImages.length) {
				return;
			}
			loadLazyImages(lazyImages);
		});
		siteNavigation.addEventListener('focusin', () => {
			const lazyImages = siteNavigation.querySelectorAll('img[data-src]');
			if (!lazyImages.length) {
				return;
			}
			loadLazyImages(lazyImages);
		});
		document._vuPrimaryNavListenersAdded = true;
	}
};

/**
 * Find an open menu item.
 */
export const findOpenMenuItem = () => {
	const openItem = siteNavigation.querySelector(`.menu-item.${activeClass}`);

	return openItem;
};

/**
 * Toggle a menu item.
 *
 * @param {HTMLElement} menuToggleButton The menu toggle button.
 */
const toggleMenuItem = (menuToggleButton) => {
	const parentElement = menuToggleButton.parentNode;

	// If the menu item is already open, close it and bail.
	const parentToggled = containsClassName(parentElement, activeClass);
	if (parentToggled) {
		closeOpenMenuItem();
		return;
	}

	// Find and close an already open menu item.
	const openItem = findOpenMenuItem();
	if (openItem) {
		closeOpenMenuItem();
	}

	// If mobile drawer is open, set all top level menu items to inert.
	if (isMobileDrawerOpen()) {
		const topLevelMenuItems = parentElement.parentNode.children;
		for (const item of topLevelMenuItems) {
			const itemLink = item.querySelector('button, a');
			if (itemLink) {
				itemLink.setAttribute('inert', '');
			}
		}
	}

	// Add the menu item active class.
	parentElement.classList.add(activeClass);

	// Add aria-expanded values on self.
	menuToggleButton.setAttribute('aria-expanded', 'true');

	// Add a sub menu open class to the body tag.
	document.body.classList.add(subMenuOpenClass);

	// Trap the focus inside the menu item.
	const parentSubMenu = parentElement.querySelector('.sub-menu');
	if (isMobileDrawerOpen()) {
		trapFocus(parentSubMenu, [mobileButton, backButton]);
	} else {
		const parentToggle = parentElement.querySelector(
			'.js-menu-item-toggle'
		);
		trapFocus(parentSubMenu, [parentToggle]);
	}

	// Focus the first sub menu item.
	const firstSubMenuItem = parentElement.querySelector('.sub-menu a');
	if (firstSubMenuItem) {
		setTimeout(() => {
			firstSubMenuItem.focus();
		}, 100);
	}

	// Activate the mobile back button and update the label.
	if (backButton && isMobileDrawerOpen()) {
		updateMobileBackButton(backButton);
	}
};

/**
 * Close an open menu item.
 */
export const closeOpenMenuItem = () => {
	const openItem = findOpenMenuItem();

	// Add a closing class to the body tag.
	document.body.classList.add(subMenuClosingClass);

	// Remove sub menu open class from the body tag.
	document.body.classList.remove(subMenuOpenClass);

	if (openItem) {
		// Remove the menu item active class.
		openItem.classList.remove(activeClass);

		// Remove the aria-expanded attributes.
		const ariaExpanded = openItem.querySelectorAll(
			'[aria-expanded="true"]'
		);
		if (ariaExpanded.length) {
			for (const item of ariaExpanded) {
				item.setAttribute('aria-expanded', 'false');
			}
		}

		// Hide the back button.
		hideMobileBackButton(backButton);

		// Focus the menu item toggle button element.
		const openItemToggle = openItem.querySelector('.js-menu-item-toggle');
		if (openItemToggle) {
			setTimeout(() => {
				openItemToggle.focus();
			}, 100);
		}
	}

	// Remove inert from all top level menu items.
	const inertMenuItems = siteNavigation.querySelectorAll('[inert]');
	for (const item of inertMenuItems) {
		item.removeAttribute('inert');
	}

	// Remove the closing class from the body tag after 500ms.
	setTimeout(() => {
		document.body.classList.remove(subMenuClosingClass);
	}, 500);
};

/**
 * Update the mobile navigation back button.
 *
 * @param {HTMLElement} button The back button element.
 */
const updateMobileBackButton = (button) => {
	// Add the active class.
	button.classList.add(activeClass);

	const openItem = findOpenMenuItem();
	if (!openItem) {
		return;
	}

	// Set the new back button label based on toggled menu item.
	const label = openItem.querySelector(':scope > .js-menu-item-toggle span');
	button.setAttribute('aria-label', `Back to ${label.textContent}`);

	// Set the text content and prepend the icon.
	const buttonIcon = button.querySelector('.svg-icon');
	button.textContent = label.textContent;
	if (buttonIcon) {
		button.prepend(buttonIcon);
	}
};

/**
 * Hide the mobile navigation back button.
 *
 * @param {HTMLElement} button The back button element.
 */
const hideMobileBackButton = (button) => {
	// Remove the active class.
	button.classList.remove(activeClass);
};
