import { registerBlockExtension } from '@10up/block-components';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextareaControl,
	ToggleControl,
} from '@wordpress/components';
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import ButtonIcon, {
	buttonIconAttributes,
	generateButtonIconClassNames,
	generateButtonIconInlineStyles,
} from '../../utils/components/icon/ButtonIcon';

/**
 * Additional block attributes object.
 */
const additionalAttributes = {
	accessibleText: {
		type: 'string',
		default: '',
	},
	closesDialog: {
		type: 'boolean',
		default: false,
	},
	hasMinWidth: {
		type: 'boolean',
		default: false,
	},
	triggerDialog: {
		type: 'string',
		default: '',
	},
	triggerDirectDownload: {
		type: 'boolean',
		default: false,
	},
};

/**
 * BlockEdit
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The new controls.
 */
const BlockEdit = (props) => {
	const {
		attributes: { accessibleText, hasMinWidth, triggerDirectDownload, url },
		setAttributes,
	} = props;

	return (
		<>
			<InspectorControls>
				<ButtonIcon {...props} />
				{!!url && (
					<PanelBody
						title={__('Downloadable Assets')}
						initialOpen={triggerDirectDownload}
					>
						<ToggleControl
							label={__('Direct Download')}
							help={__(
								'Trigger a direct download of the asset when enabled.'
							)}
							checked={triggerDirectDownload}
							onChange={(value) => {
								setAttributes({
									triggerDirectDownload: value,
								});
							}}
						/>
					</PanelBody>
				)}
				<PanelBody
					title={__('Accessibility')}
					initialOpen={accessibleText ? true : false}
				>
					<TextareaControl
						value={decodeEntities(accessibleText)}
						label={__('Screen Reader Label')}
						help={__(
							'Add a label to give the button more context for screen reader users.'
						)}
						onChange={(value) => {
							setAttributes({
								accessibleText: value,
							});
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<InspectorControls group="dimensions">
				<div className="full-width-control">
					<ToggleControl
						label={__('Minimum Width (240px)')}
						help={
							hasMinWidth
								? __('Minimum width applied.')
								: __('Toggle to add minimum width.')
						}
						checked={hasMinWidth}
						onChange={(value) => {
							setAttributes({
								hasMinWidth: value,
							});
						}}
					/>
				</div>
			</InspectorControls>
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
	const { hasMinWidth } = attributes;
	let className = '';
	className = generateButtonIconClassNames(attributes, className);
	if (hasMinWidth) {
		className += 'has-min-width ';
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
	let style = '';
	style = generateButtonIconInlineStyles(attributes, style);
	return style;
}

/**
 * registerBlockExtension
 *
 * A function to register the block extension.
 */
registerBlockExtension('core/button', {
	extensionName: 'core-button-ext',
	attributes: {
		...additionalAttributes,
		...buttonIconAttributes,
	},
	classNameGenerator: generateClassNames,
	inlineStyleGenerator: generateInlineStyle,
	Edit: BlockEdit,
});
