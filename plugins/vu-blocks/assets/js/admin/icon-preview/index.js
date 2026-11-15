/**
 * Icon Preview - Admin Entry Point
 *
 * Mounts the interactive icon preview grid on the ACF Site Options > Icons tab.
 */
import { createRoot } from '@wordpress/element';
import IconPreviewApp from './IconPreviewApp';

const mount = document.getElementById('vu-icon-preview-root');

if (mount) {
	const icons = window.vuIconPreview?.icons || {};
	const root = createRoot(mount);
	root.render(<IconPreviewApp icons={icons} />);
}
