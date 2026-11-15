import { RichTextToolbarButton } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { unseen } from '@wordpress/icons';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';

function ScreenReaderTextButton({ isActive, onChange, value }) {
	return (
		<RichTextToolbarButton
			icon={unseen}
			title={__('Screen Reader Text')}
			onClick={() => {
				onChange(
					toggleFormat(value, {
						type: 'vu/screen-reader-text',
					})
				);
			}}
			isActive={isActive}
		/>
	);
}

registerFormatType('vu/screen-reader-text', {
	title: __('Screen Reader Text'),
	tagName: 'span',
	className: 'screen-reader-text',
	edit: ScreenReaderTextButton,
});
