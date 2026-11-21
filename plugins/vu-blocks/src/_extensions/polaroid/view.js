/**
 * Polaroid dialog.
 */
import { getContext, getElement, store } from '@wordpress/interactivity';
import A11yDialog from 'a11y-dialog';

let polaroid;

const { state } = store('polaroid-store', {
	state: {
		get isSelected() {
			return getContext().polaroid.id === state.polaroidId;
		},
	},
	actions: {
		*open() {
			const { ref } = getElement();
			const current = polaroid;
			state.polaroidId = getContext().polaroidId;
			state.title = ref.textContent.trim();
			ref.focus({ preventScroll: true });

			// Let the selected image and dialog name render before moving focus.
			yield new Promise((resolve) =>
				window.requestAnimationFrame(resolve)
			);
			if (!current || current !== polaroid || !ref.isConnected) {
				return;
			}
			current.show();
		},
		close() {
			polaroid.hide();
		},
	},
	callbacks: {
		init() {
			const { ref } = getElement();
			const container = ref.querySelector('.c-polaroid');
			const dialog = new A11yDialog(container);
			dialog.on('hide', () => {
				container.querySelector('.c-polaroid__content').scrollTop = 0;
			});
			polaroid = dialog;
			state.polaroidId = 0;
			state.title = '';
			state.ready = true;

			return () => {
				dialog.destroy();
				if (polaroid === dialog) {
					polaroid = undefined;
					state.ready = false;
				}
			};
		},
	},
});
