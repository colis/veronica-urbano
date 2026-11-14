/**
 * Load lazy images by setting their src attribute from data-src.
 *
 * @param {NodeList|HTMLCollection|Array} images The collection of image elements to load.
 *
 * @return {void}
 */
export const loadLazyImages = (images) => {
	if (!images || typeof images.length !== 'number' || images.length === 0) {
		return;
	}

	for (const img of images) {
		if (!img) {
			continue;
		}
		const dataSrc = img.getAttribute('data-src');
		if (!dataSrc) {
			continue;
		}
		img.setAttribute('src', dataSrc);
		img.removeAttribute('data-src');
	}
};
