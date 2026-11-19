import { InspectorControls } from '@wordpress/block-editor';
import { Button, FormTokenField, PanelBody } from '@wordpress/components';
import { useDebounce } from '@wordpress/compose';
import { store as coreDataStore } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { useMemo, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export default function (props) {
	const { attributes, setAttributes } = props;
	const { selectedFilters } = attributes;

	const [searchString, setSearchString] = useState('');
	const debouncedSearch = useDebounce(setSearchString, 300);

	// Retrieve taxonomy terms from the DB when searchString changes.
	const { terms, hasResolved } = useSelect(
		(select) => {
			const query = {
				context: 'view',
				per_page: 100,
				search: searchString,
				_fields: 'id,name,slug',
			};

			const selectorArgs = ['taxonomy', 'category', query];

			return {
				terms: select(coreDataStore).getEntityRecords(...selectorArgs),
				hasResolved: select(coreDataStore).hasFinishedResolution(
					'getEntityRecords',
					selectorArgs
				),
			};
		},
		[searchString]
	);

	// Update the suggestion strings when the terms change.
	const suggestions = useMemo(() => {
		return terms ?? [];
	}, [terms]);

	// Transform selectedFilters objects to display strings for FormTokenField.
	const displayValues = useMemo(() => {
		return (selectedFilters || []).map((filter) =>
			typeof filter === 'string' ? filter : filter.name
		);
	}, [selectedFilters]);

	// Find term object by name.
	const findTermByName = (name) => {
		return suggestions.find((term) => term.name === name);
	};

	const moveFilter = (index, direction) => {
		const newIndex = direction === 'up' ? index - 1 : index + 1;
		const newSelectedFilters = [...selectedFilters];

		newSelectedFilters[newIndex] = selectedFilters[index];
		newSelectedFilters[index] = selectedFilters[newIndex];

		setAttributes({ selectedFilters: newSelectedFilters });
	};

	const removeFilter = (index) => {
		const newSelectedFilters = [
			...selectedFilters.slice(0, index),
			...selectedFilters.slice(index + 1),
		];

		setAttributes({ selectedFilters: newSelectedFilters });
	};

	return (
		<InspectorControls>
			<PanelBody title={__('Settings')}>
				<FormTokenField
					displayTransform={(filter) => filter.replace('&amp;', '&')}
					label={__('Select Taxonomy Terms')}
					value={displayValues}
					suggestions={
						hasResolved ? suggestions.map((term) => term.name) : []
					}
					onChange={(newDisplayValues) => {
						// Convert display values back to term objects.
						const newSelectedFilters = newDisplayValues.map(
							(displayValue) => {
								// Check if it's already an object (for existing selections).
								const existingFilter = selectedFilters.find(
									(filter) =>
										typeof filter === 'object' &&
										filter.name === displayValue
								);

								if (existingFilter) {
									return existingFilter;
								}

								// Find the term object for new selections.
								const termObject = findTermByName(displayValue);
								return termObject || displayValue;
							}
						);

						setAttributes({
							selectedFilters: newSelectedFilters,
						});
					}}
					onInputChange={debouncedSearch}
					placeholder={__('Type to search for taxonomy terms')}
				/>
			</PanelBody>
			{!!selectedFilters?.length && (
				<PanelBody title={__('Adjust Order')}>
					<div style={{ marginTop: '20px' }}>
						{selectedFilters.map((filter, index) => {
							const displayName =
								typeof filter === 'string'
									? filter
									: filter.name;
							return (
								<div
									key={
										typeof filter === 'object'
											? filter.id
											: filter
									}
									style={{
										display: 'flex',
										justifyContent: 'space-between',
										alignItems: 'center',
									}}
								>
									<div>
										<Button
											disabled={index === 0}
											icon="arrow-up-alt2"
											label={__('Move up')}
											onClick={() =>
												moveFilter(index, 'up')
											}
											style={{
												marginRight: '5px',
											}}
										/>
										<Button
											disabled={
												index ===
												selectedFilters.length - 1
											}
											icon="arrow-down-alt2"
											label={__('Move down')}
											onClick={() =>
												moveFilter(index, 'down')
											}
											variant="secondary"
										/>
									</div>
									<span>{displayName}</span>
									<Button
										icon="trash"
										isDestructive
										label={__('Remove')}
										onClick={() => removeFilter(index)}
									/>
								</div>
							);
						})}
					</div>
				</PanelBody>
			)}
		</InspectorControls>
	);
}
