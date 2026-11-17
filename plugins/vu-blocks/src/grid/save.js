import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function ({ attributes }) {
	const { className, columnsDesktop, columnsMobile, columnsTablet } =
		attributes;

	const classNames = [className || '', 'wp-block-vu-grid'];

	const blockPropsSettings = {
		className: classNames.filter(Boolean).join(' '),
		style: {
			'--grid-columns-desktop': `${columnsDesktop}`,
			'--grid-columns-tablet': `${columnsTablet}`,
			'--grid-columns-mobile': `${columnsMobile}`,
		},
	};

	const blockProps = useBlockProps.save(blockPropsSettings);

	return (
		<div {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
}
