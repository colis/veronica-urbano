import {
	InspectorControls,
	MediaPlaceholder,
	MediaReplaceFlow,
} from '@wordpress/block-editor';
import { PanelBody, PanelRow, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Additional block attributes object.
 */
export const mobileImageAttributes = {
	hasMobileImage: {
		type: 'boolean',
		default: false,
	},
	mobileImage: {
		type: 'string',
		default: '',
	},
};

export default function (props) {
	const { attributes, setAttributes } = props;
	const { hasMobileImage, mobileImage } = attributes;

	return (
		<InspectorControls>
			<PanelBody
				title={__('Mobile Image')}
				initialOpen={hasMobileImage ? true : false}
			>
				<ToggleControl
					label={__('Add mobile image')}
					checked={hasMobileImage}
					onChange={(value) => {
						setAttributes({
							hasMobileImage: value,
						});
					}}
				/>
				{hasMobileImage && (
					<>
						{mobileImage && (
							<PanelRow>
								<img alt="" src={mobileImage} />
							</PanelRow>
						)}
						<PanelRow>
							{mobileImage ? (
								<MediaReplaceFlow
									accept="image/*"
									allowedTypes={['image']}
									mediaURL={mobileImage}
									name={__('Replace Image')}
									onSelect={({ url }) => {
										setAttributes({
											mobileImage: url,
										});
									}}
								/>
							) : (
								<MediaPlaceholder
									accept="image/*"
									allowedTypes={['image']}
									labels={{
										title: __('Select the mobile image'),
									}}
									onSelect={({ url }) => {
										setAttributes({
											mobileImage: url,
										});
									}}
								/>
							)}
							{mobileImage && (
								<button
									type="button"
									data-toolbar-item="true"
									aria-expanded="false"
									aria-haspopup="true"
									className="components-button components-toolbar__control"
									onClick={() =>
										setAttributes({
											mobileImage: '',
										})
									}
								>
									{__('Remove Image')}
								</button>
							)}
						</PanelRow>
					</>
				)}
			</PanelBody>
		</InspectorControls>
	);
}
