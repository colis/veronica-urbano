import { unregisterBlockVariation } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';

/**
 * Block variation settings.
 */
domReady(() => {
	/**
	 * Remove variations.
	 */
	/* Group - Grid */
	unregisterBlockVariation('core/group', 'group-grid');

	/**
	 * Add variations.
	 */
});
