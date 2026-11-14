import { __ } from '@wordpress/i18n';

/**
 * Disable placeholder links.
 *
 * @package
 * @since 1.0.0
 */
export const disableNullLinks = () => {
	const nullMenuLinks = document.querySelectorAll('a[href="#"]');

	if (!nullMenuLinks.length) {
		return;
	}

	nullMenuLinks.forEach((link) => {
		link.addEventListener('click', (event) => {
			event.preventDefault();
		});
	});
};

/**
 * Link opens in new tab warning for screen readers.
 *
 * @package
 * @since 1.0.0
 */
export const externalLinkWarning = () => {
	const externalLinks = document.querySelectorAll(
		'a[target="_blank"]:not([aria-label])'
	);

	if (!externalLinks.length) {
		return;
	}

	const label = __('opens in a new tab', 'vu');

	externalLinks.forEach((link) => {
		let linkLabel = link.textContent;
		const img = link.querySelector('img');

		if (img) {
			linkLabel = img.getAttribute('alt') || '';
		}
		link.setAttribute('aria-label', `${linkLabel} (${label})`);
	});
};

/**
 * Add tabindex to anchor link target elements, and set focus on click.
 *
 * @package
 * @since 1.0.0
 */
export const tabIndexAnchorLinks = () => {
	const siteMain = document.getElementById('main');

	if (!siteMain) {
		return;
	}

	const anchorLinks = siteMain.querySelectorAll('a[href^="#"]');

	if (!anchorLinks.length) {
		return;
	}

	anchorLinks.forEach((link) => {
		const targetHref = link.getAttribute('href');

		if (targetHref.length === 1) {
			return;
		}

		// Find the corresponding element and set tabindex on it.
		const targetElement = document.getElementById(targetHref.substring(1));

		if (!targetElement) {
			return;
		}

		targetElement.setAttribute('tabindex', '0');

		// Track clicks on the link and move focus to the target element.
		link.addEventListener('click', () => {
			targetElement.focus();
		});
	});
};
