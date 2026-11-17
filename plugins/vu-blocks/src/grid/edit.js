import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import Inspector from './inspector';

export default function (props) {
	const { attributes } = props;
	const { className, columnsDesktop, columnsMobile, columnsTablet } =
		attributes;
	const classNames = [className || '', 'wp-block-vu-grid'];

	const blockProps = useBlockProps({
		className: classNames.filter(Boolean).join(' '),
		style: {
			'--grid-columns-desktop': `${columnsDesktop}`,
			'--grid-columns-tablet': `${columnsTablet}`,
			'--grid-columns-mobile': `${columnsMobile}`,
		},
	});

	const deviceType = useSelect((select) => {
		return select('core/editor').getDeviceType();
	}, []);

	return (
		<>
			<Inspector {...props} />
			<div {...blockProps}>
				<InnerBlocks
					orientation={
						deviceType === 'Desktop' ? 'horizontal' : 'vertical'
					}
				/>
			</div>
		</>
	);
}
