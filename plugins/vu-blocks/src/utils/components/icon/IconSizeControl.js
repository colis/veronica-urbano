import { RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import {
	DEFAULT_ICON_SIZE,
	ICON_SIZE_STEP,
	MAX_ICON_SIZE,
	MIN_ICON_SIZE,
	normalizeIconSize,
} from './iconConfig';

/**
 * Shared icon size control.
 *
 * @param {Object}   props                 Component props.
 * @param {number}   props.defaultIconSize Default icon size in px.
 * @param {string}   props.iconSize        Icon size attribute.
 * @param {Function} props.setAttributes   Set attributes callback.
 *
 * @return {HTMLElement} Range control.
 */
export default function IconSizeControl({
	defaultIconSize = DEFAULT_ICON_SIZE,
	iconSize,
	setAttributes,
}) {
	return (
		<RangeControl
			label={__('Icon Size (px)')}
			value={normalizeIconSize(iconSize, defaultIconSize)}
			min={MIN_ICON_SIZE}
			max={MAX_ICON_SIZE}
			step={ICON_SIZE_STEP}
			onChange={(value) =>
				setAttributes({
					iconSize:
						typeof value === 'number'
							? String(value)
							: String(defaultIconSize),
				})
			}
			withInputField
		/>
	);
}
