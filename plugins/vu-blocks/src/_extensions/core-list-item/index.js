import { registerBlockExtension } from '@10up/block-components';
import {
	InspectorControls,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

const YearControls = ({ clientId, attributes, setAttributes }) => {
	const isFeaturedYears = useSelect(
		(select) => {
			const { getBlockRootClientId, getBlock } = select(blockEditorStore);
			const parent = getBlock(getBlockRootClientId(clientId));
			return (
				parent?.name === 'core/list' &&
				parent.attributes.ordered &&
				(parent.attributes.className || '')
					.split(/\s+/)
					.includes('is-style-featured-years')
			);
		},
		[clientId]
	);

	if (!isFeaturedYears) {
		return null;
	}

	return (
		<InspectorControls>
			<PanelBody title={__('Year label', 'vu')}>
				<TextControl
					label={__('Year or range', 'vu')}
					help={__('For example: 2010 or 2011–2012.', 'vu')}
					value={attributes.featuredYear}
					onChange={(featuredYear) => setAttributes({ featuredYear })}
				/>
			</PanelBody>
		</InspectorControls>
	);
};

registerBlockExtension('core/list-item', {
	extensionName: 'core-list-item-years',
	attributes: {
		featuredYear: { type: 'string', default: '' },
	},
	classNameGenerator: () => '',
	inlineStyleGenerator: ({ featuredYear }) => {
		if (!featuredYear) {
			return undefined;
		}
		// Escape CSS string delimiters and line breaks before quoting the label.
		const label = featuredYear.replace(
			/["\\\n\r\f]/g,
			(character) => `\\${character.codePointAt(0).toString(16)} `
		);
		return { '--featured-year': `"${label}"` };
	},
	Edit: YearControls,
});
