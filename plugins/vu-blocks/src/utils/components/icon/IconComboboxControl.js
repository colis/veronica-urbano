import { ComboboxControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

/**
 * Shared ComboboxControl component for icons.
 *
 * @param {Object}                  props
 * @param {string}                  props.label
 * @param {string}                  props.value
 * @param {(value: string) => void} props.onChange
 * @param {Array}                   props.options
 * @param {(value: string) => void} props.onFilterValueChange
 * @param {string}                  [props.placeholder]
 *
 * @return {Element} The IconComboboxControl component.
 */
export const IconComboboxControl = ({
	label = __('Icon'),
	value,
	onChange,
	options,
	onFilterValueChange,
	placeholder = __('Search icons…'),
}) => {
	return (
		<ComboboxControl
			label={label}
			value={value || ''}
			onChange={onChange}
			options={options}
			placeholder={placeholder}
			onFilterValueChange={onFilterValueChange}
			__experimentalRenderItem={({ item }) => {
				const itemDataUri = item?.dataUri;
				const itemCategory = item?.category;
				const itemLabel = item?.baseLabel || item?.label;

				return (
					<div
						style={{
							display: 'flex',
							alignItems: 'center',
							gap: '0.5rem',
							padding: '0.25rem 0',
						}}
					>
						<span
							aria-hidden="true"
							style={{
								width: '1.5rem',
								height: '1.5rem',
								flex: '0 0 1.5rem',
								backgroundImage: itemDataUri
									? `url("${itemDataUri}")`
									: undefined,
								backgroundRepeat: 'no-repeat',
								backgroundPosition: 'center',
								backgroundSize: 'contain',
							}}
						/>
						<span
							style={{
								display: 'flex',
								flexDirection: 'column',
								gap: '2px',
							}}
						>
							<span>{itemLabel}</span>
							{itemCategory ? (
								<span
									style={{
										fontSize: '0.6875rem',
										opacity: 0.8,
									}}
								>
									{itemCategory}
								</span>
							) : null}
						</span>
					</div>
				);
			}}
		/>
	);
};
