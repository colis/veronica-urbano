import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { buttonIconAttributes } from './iconAttributes';
import {
	getLeadingIconEnableAttributes,
	getTrailingIconEnableAttributes,
} from './iconConfig';
import {
	generateButtonIconClassNames,
	generateButtonIconInlineStyles,
} from './iconGenerators';
import IconInspectorFields from './IconInspectorFields';

export {
	buttonIconAttributes,
	generateButtonIconClassNames,
	generateButtonIconInlineStyles,
};

export default function ButtonIcon(props) {
	const { attributes, setAttributes } = props;
	const { className, hasLeadingIcon, hasTrailingIcon } = attributes;
	const isStyleDownload = className?.includes('is-style-download');

	// For the "Download" style button, disable the icon attributes and do not render the icon controls.
	// The Download button icon is handled separately.
	if (isStyleDownload) {
		setAttributes({
			hasLeadingIcon: false,
			hasTrailingIcon: false,
		});
		return null;
	}

	return (
		<PanelBody
			title={__('Icons')}
			initialOpen={hasLeadingIcon || hasTrailingIcon ? true : false}
		>
			<ToggleControl
				label={__('Add Leading Icon')}
				checked={hasLeadingIcon}
				disabled={hasTrailingIcon}
				onChange={(value) => {
					setAttributes(
						value
							? {
									...getLeadingIconEnableAttributes(
										attributes
									),
									hasTrailingIcon: false,
								}
							: { hasLeadingIcon: false }
					);
				}}
			/>
			<ToggleControl
				label={__('Add Trailing Icon')}
				checked={hasTrailingIcon}
				disabled={hasLeadingIcon}
				onChange={(value) => {
					setAttributes(
						value
							? getTrailingIconEnableAttributes(attributes)
							: { hasTrailingIcon: false }
					);
				}}
			/>
			{(hasLeadingIcon || hasTrailingIcon) && (
				<IconInspectorFields
					attributes={attributes}
					setAttributes={setAttributes}
				/>
			)}
		</PanelBody>
	);
}
