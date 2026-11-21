import {
	store as blockEditorStore,
	MediaUpload,
	MediaUploadCheck,
	RichTextToolbarButton,
} from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useRef } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { image, reset } from '@wordpress/icons';
import {
	applyFormat,
	getActiveFormat,
	registerFormatType,
	removeFormat,
} from '@wordpress/rich-text';

const FORMAT_NAME = 'vu/polaroid';

function hasPolaroid(formats, polaroidId) {
	return formats?.some(
		(format) =>
			format.type === FORMAT_NAME && format.attributes?.id === polaroidId
	);
}

/**
 * Expand an existing phrase and reject overlapping links or image associations.
 *
 * @param {Object}           value      RichText value and current selection.
 * @param {string|undefined} polaroidId Active image association, if any.
 * @return {Object} Phrase boundaries and whether an image can be attached.
 */
function getPolaroidSelection(value, polaroidId) {
	let { start, end } = value;
	const { formats, text } = value;
	if (start === undefined || end === undefined) {
		return { start, end, canAttach: false };
	}

	if (polaroidId) {
		while (start > 0 && hasPolaroid(formats[start - 1], polaroidId)) {
			start--;
		}
		while (end < text.length && hasPolaroid(formats[end], polaroidId)) {
			end++;
		}
	}

	const overlapsInteractiveText = formats
		.slice(start, Math.max(end, start + 1))
		.some((items) =>
			items?.some(
				(format) =>
					format.type === 'core/link' ||
					(format.type === FORMAT_NAME &&
						format.attributes?.id !== polaroidId)
			)
		);

	return {
		start,
		end,
		canAttach:
			Boolean(polaroidId || text.slice(start, end).trim()) &&
			!overlapsInteractiveText,
	};
}

function Polaroid({ value, onChange }) {
	const isParagraph = useSelect(
		(select) =>
			select(blockEditorStore).getSelectedBlock()?.name ===
			'core/paragraph',
		[]
	);
	const selection = useRef(null);
	if (!isParagraph) {
		return null;
	}
	const activeId = getActiveFormat(value, FORMAT_NAME)?.attributes?.id;
	const { start, end, canAttach } = getPolaroidSelection(value, activeId);

	function attachPolaroid({ id }) {
		const saved = selection.current;
		onChange(
			applyFormat(
				saved.value,
				{
					type: FORMAT_NAME,
					attributes: { id: String(id), buttonType: 'button' },
				},
				saved.start,
				saved.end
			)
		);
	}

	function removePolaroid() {
		onChange(removeFormat(value, FORMAT_NAME, start, end));
	}

	return (
		<>
			<MediaUploadCheck>
				<MediaUpload
					allowedTypes={['image']}
					value={Number(activeId) || undefined}
					onSelect={attachPolaroid}
					render={({ open }) => (
						<RichTextToolbarButton
							icon={image}
							title={
								activeId
									? __('Replace polaroid', 'vu-blocks')
									: __('Attach polaroid', 'vu-blocks')
							}
							isActive={Boolean(activeId)}
							disabled={!canAttach}
							onClick={() => {
								selection.current = { value, start, end };
								open();
							}}
						/>
					)}
				/>
			</MediaUploadCheck>
			{activeId && (
				<RichTextToolbarButton
					icon={reset}
					title={__('Remove polaroid', 'vu-blocks')}
					onClick={removePolaroid}
				/>
			)}
		</>
	);
}

registerFormatType(FORMAT_NAME, {
	title: __('Attach polaroid', 'vu-blocks'),
	tagName: 'button',
	className: 'c-polaroid__trigger',
	attributes: { id: 'data-polaroid-id', buttonType: 'type' },
	interactive: true,
	edit: Polaroid,
});
