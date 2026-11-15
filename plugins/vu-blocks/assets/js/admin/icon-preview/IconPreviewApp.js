/**
 * Icon Preview App
 *
 * Renders an interactive icon grid with search, grouped by category.
 * Clicking an icon opens the IconDetailModal to preview the icon.
 */
import { Button, SearchControl } from '@wordpress/components';
import { useMemo, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import {
	filterIconOptions,
	getIconOptions,
	groupIconOptions,
} from '../../../../src/utils/functions/icons';
import IconDetailModal from './IconDetailModal';

export default function IconPreviewApp({ icons }) {
	const [searchInput, setSearchInput] = useState('');
	const [selectedIcon, setSelectedIcon] = useState(null);

	const iconOptions = useMemo(
		() => getIconOptions(icons).filter((opt) => opt.value && opt.svgString),
		[icons]
	);

	const filteredIconOptions = useMemo(
		() => filterIconOptions(iconOptions, searchInput),
		[iconOptions, searchInput]
	);

	const groupedIconOptions = useMemo(
		() => groupIconOptions(filteredIconOptions),
		[filteredIconOptions]
	);

	return (
		<div className="vu-icon-preview">
			<div className="vu-icon-preview__header">
				<SearchControl
					className="vu-icon-preview__search"
					label={__('Search icons or categories', 'vu-blocks')}
					value={searchInput}
					onChange={setSearchInput}
					placeholder={__('Search icons or categories…', 'vu-blocks')}
				/>
			</div>

			{groupedIconOptions.length ? (
				groupedIconOptions.map(({ category, options }) => (
					<section
						key={category}
						className="vu-icon-preview__section"
					>
						<h3 className="vu-icon-preview__category">
							{sprintf(
								// translators: 1: category name, 2: icon count.
								__('%1$s (%2$d)', 'vu-blocks'),
								category,
								options.length
							)}
						</h3>
						<div className="vu-icon-preview__grid">
							{options.map((iconOption) => (
								<Button
									key={iconOption.value}
									type="button"
									variant="secondary"
									onClick={() => setSelectedIcon(iconOption)}
									className="vu-icon-preview__item"
								>
									<span aria-hidden="true">
										{iconOption.dataUri ? (
											<img
												src={iconOption.dataUri}
												alt=""
												width="56"
												height="56"
												className="vu-icon-preview__img"
											/>
										) : null}
									</span>
									<span className="vu-icon-preview__label">
										{iconOption.baseLabel}
									</span>
								</Button>
							))}
						</div>
					</section>
				))
			) : (
				<p>{__('No results found.', 'vu-blocks')}</p>
			)}

			{selectedIcon && (
				<IconDetailModal
					icon={selectedIcon}
					onClose={() => setSelectedIcon(null)}
				/>
			)}
		</div>
	);
}
