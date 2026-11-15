import { __ } from '@wordpress/i18n';

/**
 * Get the icon category from an icon entry.
 *
 * @param {object|string} iconEntry
 *
 * @return {string} The icon category, or an empty string if not available.
 */
export const getIconCategory = (iconEntry) => {
	if (
		iconEntry &&
		typeof iconEntry === 'object' &&
		typeof iconEntry.category === 'string'
	) {
		return iconEntry.category;
	}
	return '';
};

/**
 * Get the SVG string from an icon entry.
 *
 * @param {object|string} iconEntry The icon entry, which can be an object or a string.
 *
 * @return {string} The SVG string, or an empty string if not available.
 */
export const getIconSvgString = (iconEntry) => {
	if (typeof iconEntry === 'string') {
		return iconEntry;
	}
	if (
		iconEntry &&
		typeof iconEntry === 'object' &&
		typeof iconEntry.svg === 'string'
	) {
		return iconEntry.svg;
	}
	return '';
};

/**
 * Convert an SVG string to a data URI.
 *
 * @param {string} svgString SVG string.
 *
 * @return {string} Data URI string.
 */
export const svgToDataUri = (svgString) => {
	if (!svgString || typeof svgString !== 'string') {
		return '';
	}
	const cleaned = svgString.replace(/\s+/g, ' ').trim();
	const encoded = encodeURIComponent(cleaned)
		.replace(/'/g, '%27')
		.replace(/"/g, '%22');

	return `data:image/svg+xml,${encoded}`;
};

/**
 * Build icon options from the VU icon registry.
 *
 * @param {Record<string, object|string>|undefined|null} icons Icon registry object.
 *
 * @return {Array<{value: string, label: string, baseLabel?: string, category?: string, dataUri?: string}>} Array of icon options.
 */
export const getIconOptions = (icons) => {
	const iconKeys = Object.keys(icons || {}).sort((a, b) =>
		a.localeCompare(b)
	);

	return [
		{ value: '', label: __('Select an icon…') },
		...iconKeys.map((iconKey) => {
			const baseLabel = iconKey
				.replace(/[-_]+/g, ' ')
				.replace(/\b\w/g, (char) => char.toUpperCase());
			const iconEntry = icons?.[iconKey];
			const category = getIconCategory(iconEntry);
			const svgString = getIconSvgString(iconEntry);
			const dataUri = svgToDataUri(svgString);
			const label = category ? `${baseLabel} (${category})` : baseLabel;

			return {
				value: iconKey,
				label,
				baseLabel,
				category,
				dataUri,
				svgString,
			};
		}),
	];
};

/**
 * Filter an icon options array using the current filter input.
 *
 * @param {Array<{value: string, label: string, baseLabel?: string, category?: string}>} iconOptions
 * @param {string}                                                                       iconFilterValue
 *
 * @return {Array} Filtered options.
 */
export const filterIconOptions = (iconOptions, iconFilterValue) => {
	const normalized = String(iconFilterValue || '')
		.trim()
		.toLowerCase();
	if (!normalized) {
		return iconOptions;
	}

	return (iconOptions || []).filter((option) => {
		if (!option) {
			return false;
		}

		const category = String(option.category || '').toLowerCase();
		if (!category) {
			return false;
		}

		const label = String(option.label || '').toLowerCase();
		const value = String(option.value || '').toLowerCase();

		return (
			label.includes(normalized) ||
			value.includes(normalized) ||
			category.includes(normalized)
		);
	});
};

/**
 * Group icon options by category, sorted alphabetically.
 *
 * @param {Array<{value: string, label: string, baseLabel?: string, category?: string}>} iconOptions
 *
 * @return {Array<{category: string, options: Array}>} Grouped and sorted options.
 */
export const groupIconOptions = (iconOptions) => {
	const uncategorized = __('Uncategorized');
	const grouped = new Map();

	(iconOptions || []).forEach((iconOption) => {
		const groupName = iconOption.category || uncategorized;
		const current = grouped.get(groupName) || [];
		current.push(iconOption);
		grouped.set(groupName, current);
	});

	return Array.from(grouped.entries())
		.sort(([a], [b]) => a.localeCompare(b))
		.map(([category, options]) => ({
			category,
			options: options.sort((a, b) => {
				const aLabel = String(a.baseLabel || a.label || a.value || '');
				const bLabel = String(b.baseLabel || b.label || b.value || '');
				return aLabel.localeCompare(bLabel);
			}),
		}));
};
