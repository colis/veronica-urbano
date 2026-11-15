import { registerBlockExtension } from '@10up/block-components';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	// eslint-disable-next-line
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import MobileImage, {
	mobileImageAttributes,
} from '../../utils/components/MobileImage';

/**
 * Additional block attributes object.
 */
const additionalAttributes = {
	...mobileImageAttributes,
	minHeightDesktop: {
		type: 'string',
		default: '',
	},
};

/**
 * BlockEdit
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The block edit component.
 */
const BlockEdit = (props) => {
	const {
		attributes: { className, imageFill, minHeightDesktop },
		setAttributes,
	} = props;
	const profileCard =
		className !== undefined && className.includes('profile-card')
			? true
			: false;

	// Set default attributes.
	useEffect(() => {
		if (!profileCard) {
			setAttributes({
				imageFill: true,
			});
			return;
		}

		setAttributes({
			imageFill: false,
		});
	}, [profileCard, setAttributes]);

	return (
		<>
			{imageFill && (
				<InspectorControls group="settings">
					<PanelBody
						title={__('Minimum Height')}
						initialOpen={minHeightDesktop ? true : false}
					>
						<UnitControl
							label={__('Desktop minimum height (px)')}
							min={0}
							unit={'px'}
							value={minHeightDesktop}
							onChange={(value) =>
								setAttributes({
									minHeightDesktop: value,
								})
							}
						/>
					</PanelBody>
				</InspectorControls>
			)}
			<MobileImage {...props} />
		</>
	);
};

/**
 * generateClassNames
 *
 * @param {Object} attributes The block attributes.
 * @return {string} The new className string.
 */
function generateClassNames(attributes) {
	const { imageFill, minHeightDesktop } = attributes;
	let className = '';
	if (imageFill && minHeightDesktop) {
		className += ' has-min-height-desktop';
	}
	return className;
}

/**
 * generateInlineStyle
 *
 * @param {Object} attributes The block attributes.
 * @return {Object} The inline style object.
 */
function generateInlineStyle(attributes) {
	const { imageFill, minHeightDesktop } = attributes;
	let style;
	if (imageFill && minHeightDesktop) {
		style = {
			'--min-height-desktop': minHeightDesktop,
		};
	}

	return style;
}

/**
 * registerBlockExtension
 *
 * A function to register the block extension.
 */
registerBlockExtension('core/media-text', {
	extensionName: 'core-media-text-ext',
	attributes: additionalAttributes,
	classNameGenerator: generateClassNames,
	inlineStyleGenerator: generateInlineStyle,
	Edit: BlockEdit,
});
