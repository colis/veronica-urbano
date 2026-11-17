import { InspectorControls } from '@wordpress/block-editor';
import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function ({ attributes, setAttributes }) {
	const { columnsDesktop, columnsMobile, columnsTablet } = attributes;

	return (
		<InspectorControls>
			<Panel>
				<PanelBody title={__('Grid Settings')}>
					<PanelRow>
						<RangeControl
							label={__('Desktop Columns')}
							value={columnsDesktop}
							onChange={(newColumnsDesktop) =>
								setAttributes({
									columnsDesktop: Number(newColumnsDesktop),
								})
							}
							min={1}
							max={6}
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label={__('Tablet Columns')}
							value={columnsTablet}
							onChange={(newColumnsTablet) =>
								setAttributes({
									columnsTablet: Number(newColumnsTablet),
								})
							}
							min={1}
							max={6}
						/>
					</PanelRow>
					<PanelRow>
						<RangeControl
							label={__('Mobile Columns')}
							value={columnsMobile}
							onChange={(newColumnsMobile) =>
								setAttributes({
									columnsMobile: Number(newColumnsMobile),
								})
							}
							min={1}
							max={6}
						/>
					</PanelRow>
				</PanelBody>
			</Panel>
		</InspectorControls>
	);
}
