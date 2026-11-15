import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { paragraphIconAttributes } from './iconAttributes';
import {
	getLeadingIconEnableAttributes,
	PARAGRAPH_DEFAULT_ICON_SIZE,
} from './iconConfig';
import {
	generateParagraphIconClassNames,
	generateParagraphIconInlineStyles,
} from './iconGenerators';
import IconInspectorFields from './IconInspectorFields';
import { isParagraphIconStyle } from './paragraphIconConfig';

export {
	generateParagraphIconClassNames,
	generateParagraphIconInlineStyles,
	paragraphIconAttributes,
};

/**
 * Paragraph icon inspector controls.
 *
 * @param {Object} props Block props.
 * @return {HTMLElement} The inspector controls.
 */
export default function ParagraphIcon(props) {
	const { attributes, setAttributes } = props;
	const { className, hasLeadingIcon } = attributes;
	const isIconStyle = isParagraphIconStyle(className);

	if (!isIconStyle) {
		return null;
	}

	return (
		<PanelBody
			title={__('Icon')}
			initialOpen={hasLeadingIcon ? true : false}
		>
			<ToggleControl
				label={__('Add Leading Icon')}
				checked={hasLeadingIcon}
				onChange={(value) => {
					setAttributes(
						value
							? getLeadingIconEnableAttributes(attributes, {
									defaultIconSize:
										PARAGRAPH_DEFAULT_ICON_SIZE,
								})
							: { hasLeadingIcon: false }
					);
				}}
			/>
			{hasLeadingIcon && (
				<IconInspectorFields
					attributes={attributes}
					defaultIconSize={PARAGRAPH_DEFAULT_ICON_SIZE}
					setAttributes={setAttributes}
				/>
			)}
		</PanelBody>
	);
}
