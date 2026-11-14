/**
 * Handles the display logic of the location nav callout components.
 */
export const locationNavCallouts = () => {
	const locationTabs = document.querySelector('.js-location-tabs');

	if (!locationTabs) {
		return;
	}

	const navCallouts = locationTabs.querySelectorAll(
		'.c-location-tabs__nav-callout-part > a'
	);

	if (!navCallouts.length) {
		return;
	}

	const locationTabLabels = [
		...locationTabs.querySelectorAll('.c-location-tabs__tab-label'),
	].map((locationTabLabel) => locationTabLabel.id);

	if (!locationTabLabels.length) {
		return;
	}

	navCallouts.forEach((navCallout) => {
		const hash = navCallout.hash.replace('#', '');

		if (!locationTabLabels.includes(hash)) {
			navCallout.parentElement.remove();
			return;
		}

		navCallout.parentElement.classList.add('visible');
	});
};
