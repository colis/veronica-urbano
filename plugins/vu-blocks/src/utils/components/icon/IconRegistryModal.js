import { Button, Modal, SearchControl } from '@wordpress/components';
import { useMemo, useState } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import {
	filterIconOptions,
	getIconOptions,
	groupIconOptions,
} from '../../functions/icons';

export default function IconRegistryModal({ icons, value, onChange, onClear }) {
	const [isOpen, setIsOpen] = useState(false);
	const [searchInput, setSearchInput] = useState('');

	const iconOptions = useMemo(() => {
		return getIconOptions(icons).filter((iconOption) => iconOption.value);
	}, [icons]);

	const selectedIcon = useMemo(() => {
		return (
			iconOptions.find((iconOption) => iconOption.value === value) || null
		);
	}, [iconOptions, value]);

	const filteredIconOptions = useMemo(() => {
		return filterIconOptions(iconOptions, searchInput);
	}, [iconOptions, searchInput]);

	const groupedIconOptions = useMemo(() => {
		return groupIconOptions(filteredIconOptions);
	}, [filteredIconOptions]);

	const buttonLabel = value
		? // translators: %s is the selected icon name.
			sprintf(__('Selected: %s'), selectedIcon?.baseLabel || value)
		: __('Choose icon');

	return (
		<>
			<div className="vu-icon-registry-modal__trigger-row">
				<Button
					type="button"
					variant="secondary"
					onClick={() => setIsOpen(true)}
					__next40pxDefaultSize
				>
					{buttonLabel}
				</Button>
				<Button
					type="button"
					variant="tertiary"
					onClick={onClear}
					disabled={!value}
					__next40pxDefaultSize
				>
					{__('Clear icon')}
				</Button>
			</div>
			{isOpen && (
				<Modal
					title={__('Icon Registry')}
					onRequestClose={() => setIsOpen(false)}
					isFullScreen
				>
					<div className="vu-icon-registry-modal">
						<div className="vu-icon-registry-modal__header">
							<SearchControl
								className="vu-icon-registry-modal__search"
								label={__('Search icons or categories')}
								value={searchInput}
								onChange={setSearchInput}
								placeholder={__('Search icons or categories…')}
							/>
							<Button
								type="button"
								variant="secondary"
								onClick={() => {
									onClear();
								}}
								disabled={!value}
								__next40pxDefaultSize
							>
								{__('Clear icon')}
							</Button>
						</div>
						{groupedIconOptions.length ? (
							groupedIconOptions.map(({ category, options }) => (
								<section
									key={category}
									className="vu-icon-registry-modal__section"
								>
									<h2 className="vu-icon-registry-modal__category">
										{sprintf(
											// translators: 1: icon category name, 2: number of icons in the category.
											__('%1$s (%2$d)'),
											category,
											options.length
										)}
									</h2>
									<div className="vu-icon-registry-modal__grid">
										{options.map((iconOption) => {
											const isSelected =
												iconOption.value === value;

											return (
												<Button
													key={iconOption.value}
													type="button"
													variant={
														isSelected
															? 'primary'
															: 'secondary'
													}
													onClick={() => {
														onChange(
															iconOption.value
														);
														setIsOpen(false);
													}}
													className="vu-icon-registry-modal__item"
												>
													<span aria-hidden="true">
														{iconOption.dataUri ? (
															<img
																src={
																	iconOption.dataUri
																}
																alt=""
																width="56"
																height="56"
																className="vu-icon-registry-modal__preview"
															/>
														) : null}
													</span>
													<span>
														{iconOption.baseLabel ||
															iconOption.label}
													</span>
												</Button>
											);
										})}
									</div>
								</section>
							))
						) : (
							<p>{__('No results found.')}</p>
						)}
					</div>
				</Modal>
			)}
		</>
	);
}
