import { registerBlockExtension } from '@10up/block-components';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ParagraphIcon, {
	generateParagraphIconClassNames,
	generateParagraphIconInlineStyles,
	paragraphIconAttributes,
} from '../../utils/components/icon/ParagraphIcon';

/**
 * Additional block attributes object.
 */
const additionalAttributes = {
	tagSize: {
		type: 'string',
		default: 'lg',
	},
	tagEmphasis: {
		type: 'string',
		default: 'bold',
	},
	tagStatus: {
		type: 'string',
		default: 'info',
	},
	...paragraphIconAttributes,
};

/**
 * BlockEdit
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The new controls.
 */
const BlockEdit = (props) => {
	const {
		attributes: { className, tagEmphasis, tagSize, tagStatus },
		setAttributes,
	} = props;

	const isStyleTag = className && className.includes('is-style-tag');

	const tagSizeOptions = [
		{ label: __('Select a tag size…'), value: '', disabled: true },
		{ label: __('Large'), value: 'lg' },
		{ label: __('Small'), value: 'sm' },
	];

	const tagEmphasisOptions = [
		{ label: __('Select a tag emphasis…'), value: '', disabled: true },
		{ label: __('Bold'), value: 'bold' },
		{ label: __('Subtle'), value: 'subtle' },
	];

	const tagStatusOptions = [
		{ label: __('Select a tag status…'), value: '', disabled: true },
		{ label: __('Neutral'), value: 'neutral' },
		{ label: __('Accent'), value: 'accent' },
		{ label: __('Info'), value: 'info' },
		{ label: __('Success'), value: 'success' },
		{ label: __('Warning'), value: 'warning' },
		{ label: __('Danger'), value: 'danger' },
		{ label: __('Highlight'), value: 'highlight' },
	];

	return (
		<InspectorControls>
			{isStyleTag && (
				<PanelBody title={__('Tag Settings')} initialOpen={true}>
					<SelectControl
						label={__('Select Tag Size')}
						value={tagSize}
						options={tagSizeOptions}
						onChange={(value) =>
							setAttributes({
								tagSize: value,
							})
						}
					/>
					<SelectControl
						label={__('Select Tag Emphasis')}
						value={tagEmphasis}
						options={tagEmphasisOptions}
						onChange={(value) =>
							setAttributes({
								tagEmphasis: value,
							})
						}
					/>
					<SelectControl
						label={__('Select Tag Status')}
						value={tagStatus}
						options={tagStatusOptions}
						onChange={(value) =>
							setAttributes({
								tagStatus: value,
							})
						}
					/>
				</PanelBody>
			)}
			<ParagraphIcon {...props} />
		</InspectorControls>
	);
};

/**
 * generateClassNames
 *
 * @param {Object} attributes The block attributes.
 * @return {string} The new className string.
 */
function generateClassNames(attributes) {
	const { className, tagEmphasis, tagSize, tagStatus } = attributes;
	let newClassName = '';
	const isStyleTag = className && className.includes('is-style-tag');
	if (isStyleTag) {
		if (tagSize) {
			newClassName += `tag-${tagSize}`;
		}
		if (tagEmphasis && tagStatus) {
			newClassName += ` tag-${tagEmphasis}-${tagStatus}`;
		}
	}
	newClassName = generateParagraphIconClassNames(attributes, newClassName);
	return newClassName;
}

/**
 * generateInlineStyle
 *
 * @param {Object} attributes The block attributes.
 * @return {Object} The inline style object.
 */
function generateInlineStyle(attributes) {
	return generateParagraphIconInlineStyles(attributes, {});
}

/**
 * registerBlockExtension
 *
 * A function to register the block extension.
 */
registerBlockExtension('core/paragraph', {
	extensionName: 'core-paragraph-ext',
	attributes: additionalAttributes,
	classNameGenerator: generateClassNames,
	inlineStyleGenerator: generateInlineStyle,
	Edit: BlockEdit,
});
