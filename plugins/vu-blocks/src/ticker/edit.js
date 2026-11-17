/* global vuCore */
import {
	BlockControls,
	InnerBlocks,
	InspectorControls,
	store as blockEditorStore,
	useBlockProps,
} from '@wordpress/block-editor';
import { serialize } from '@wordpress/blocks';
import {
	Disabled,
	PanelBody,
	ToolbarButton,
	ToolbarGroup,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { RawHTML, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { pencil } from '@wordpress/icons';
import IconRegistryModal from '../utils/components/icon/IconRegistryModal';
import IconSizeControl from '../utils/components/icon/IconSizeControl';
import { normalizeIconSize } from '../utils/components/icon/iconConfig';
import { getIconSvgString, svgToDataUri } from '../utils/functions/icons';

export default function (props) {
	const { attributes, clientId, setAttributes } = props;
	const { className, separatorIcon, separatorIconSize } = attributes;
	const icons = typeof vuCore === 'undefined' ? undefined : vuCore.vuIcons;
	const separatorUri = svgToDataUri(getIconSvgString(icons?.[separatorIcon]));

	const [isEditing, toggleEditing] = useState(false);

	const classNames = [
		className || '',
		isEditing ? 'is-editing' : null,
		separatorUri ? 'has-separator' : null,
	];

	const blockProps = useBlockProps({
		className: classNames.filter(Boolean).join(' '),
		style: separatorUri
			? {
					'--vu-ticker-separator-icon': `url("${separatorUri}")`,
					'--vu-ticker-separator-size': `${normalizeIconSize(separatorIconSize)}px`,
				}
			: undefined,
	});

	const innerBlocks = useSelect(
		(select) => select(blockEditorStore).getBlocks(clientId),
		[clientId]
	);
	const previewHTML = serialize(innerBlocks);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Separator')}>
					<IconRegistryModal
						icons={icons}
						value={separatorIcon}
						onChange={(value) =>
							setAttributes({ separatorIcon: value || '' })
						}
						onClear={() => setAttributes({ separatorIcon: '' })}
					/>
					{separatorIcon && (
						<IconSizeControl
							iconSize={separatorIconSize}
							setAttributes={({ iconSize }) =>
								setAttributes({ separatorIconSize: iconSize })
							}
						/>
					)}
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<BlockControls>
					<ToolbarGroup label={__('Options')}>
						<ToolbarButton
							icon={pencil}
							label={
								isEditing
									? __('Preview Ticker')
									: __('Edit Ticker')
							}
							className={isEditing ? 'is-pressed' : ''}
							onClick={() => toggleEditing(!isEditing)}
						/>
					</ToolbarGroup>
				</BlockControls>
				{isEditing ? (
					<InnerBlocks
						allowedBlocks={['core/list']}
						template={[['core/list']]}
					/>
				) : (
					<Disabled>
						{['original', 'duplicate'].map((group) => (
							<RawHTML
								key={group}
								className="wp-block-vu-ticker__group"
								aria-hidden={
									group === 'duplicate' ? true : undefined
								}
								inert={group === 'duplicate' ? '' : undefined}
							>
								{previewHTML}
							</RawHTML>
						))}
					</Disabled>
				)}
			</div>
		</>
	);
}
