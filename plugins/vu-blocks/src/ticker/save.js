import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function ({ attributes }) {
	const { className } = attributes;

	const classNames = [className || '', 'wp-block-vu-ticker'];

	const blockPropsSettings = {
		className: classNames.filter(Boolean).join(' '),
	};

	const blockProps = useBlockProps.save(blockPropsSettings);

	return (
		<div {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
}
