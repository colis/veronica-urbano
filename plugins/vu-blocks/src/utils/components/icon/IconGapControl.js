import { SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	DEFAULT_ICON_GAP,
	ICON_GAP_OPTIONS,
	normalizeIconGap,
} from './iconConfig';

/**
 * Shared icon gap control.
 *
 * @param {Object}   props               Component props.
 * @param {string}   props.iconGap       Icon gap attribute.
 * @param {Function} props.setAttributes Set attributes callback.
 *
 * @return {HTMLElement} Select control.
 */
export default function IconGapControl({ iconGap, setAttributes }) {
	const iconGapOptions = [
		{ label: __('Select an icon gap…'), value: '', disabled: true },
		...ICON_GAP_OPTIONS.map((gap) => ({
			label: `${gap}px`,
			value: String(gap),
		})),
	];

	return (
		<SelectControl
			label={__('Icon Gap')}
			value={String(normalizeIconGap(iconGap))}
			options={iconGapOptions}
			onChange={(value) =>
				setAttributes({
					iconGap: value || String(DEFAULT_ICON_GAP),
				})
			}
		/>
	);
}
