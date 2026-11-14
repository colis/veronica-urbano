/**
 * Frontend JS.
 */

// Components.
import {
	disableNullLinks,
	externalLinkWarning,
	tabIndexAnchorLinks,
} from './components/links';
import { locationNavCallouts } from './components/locationNavCallouts';

// Header.
import { mobileDrawer } from './components/header/mobileDrawer';
import { primaryNav } from './components/header/primaryNav';

/* Actions */
// When the DOM is ready.
document.addEventListener('DOMContentLoaded', () => {
	// Components.
	locationNavCallouts();

	// Links.
	disableNullLinks();
	externalLinkWarning();
	tabIndexAnchorLinks();

	// Header.
	mobileDrawer();
	primaryNav();
});
