/* global vuCore */
import Spacer from '../Spacer';
import IconGapControl from './IconGapControl';
import IconRegistryModal from './IconRegistryModal';
import IconSizeControl from './IconSizeControl';

/**
 * Shared icon inspector fields.
 *
 * @param {Object}   props                 Component props.
 * @param {Object}   props.attributes      Block attributes.
 * @param {number}   props.defaultIconSize Default icon size in px.
 * @param {Function} props.setAttributes   Set attributes callback.
 *
 * @return {HTMLElement} Inspector fields.
 */
export default function IconInspectorFields({
	attributes,
	defaultIconSize,
	setAttributes,
}) {
	const { icon, iconGap, iconSize } = attributes;
	const icons = vuCore?.vuIcons;

	return (
		<>
			<Spacer height={10} />
			<IconRegistryModal
				icons={icons}
				value={icon || ''}
				onChange={(value) =>
					setAttributes({
						icon: value || '',
					})
				}
				onClear={() =>
					setAttributes({
						icon: '',
					})
				}
			/>
			<Spacer height={20} />
			<IconSizeControl
				defaultIconSize={defaultIconSize}
				iconSize={iconSize}
				setAttributes={setAttributes}
			/>
			<IconGapControl iconGap={iconGap} setAttributes={setAttributes} />
		</>
	);
}
