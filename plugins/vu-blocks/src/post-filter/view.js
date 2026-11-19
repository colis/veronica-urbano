/**
 * Post Filter block functionality
 *
 * @package
 * @since 1.0.0
 */

import { getContext, store } from '@wordpress/interactivity';

store('post-filter-store', {
	state: {
		get isFilterChecked() {
			const ctx = getContext();
			return ctx.selectedFilter === ctx.filter.slug;
		},
		get filterId() {
			const ctx = getContext();
			return `${ctx.inputName}-${ctx.filter.slug}`;
		},
	},
	actions: {
		*onFilterChange(event) {
			if (!event.target.checked) {
				return;
			}
			const ctx = getContext();
			const url = new URL(window.location.href);

			const taxonomyParameter =
				ctx.filterParameter || `query-${ctx.queryId}-${ctx.taxonomy}`;

			// Like the reference block, update selection before navigating.
			ctx.selectedFilter = event.target.value;
			if (ctx.selectedFilter !== 'tutti') {
				url.searchParams.set(taxonomyParameter, ctx.selectedFilter);
			} else {
				url.searchParams.delete(taxonomyParameter);
			}

			url.searchParams.delete(`query-${ctx.queryId}-page`);
			if (ctx.inherit) {
				url.searchParams.delete('paged');
				url.searchParams.delete('page');
				url.pathname = url.pathname.replace(/\/page\/\d+\/?$/, '/');
			}

			// Navigate.
			const { actions: navActions } =
				yield import('@wordpress/interactivity-router');
			yield navActions.navigate(url.toString());
		},
	},
	callbacks: {
		initFilters: () => {
			const ctx = getContext();
			const urlParams = new URLSearchParams(window.location.search);

			// Initialize taxonomy filters
			const categories = urlParams.get(
				ctx.filterParameter || `query-${ctx.queryId}-${ctx.taxonomy}`
			);

			ctx.selectedFilter = categories?.split(',')[0] || 'tutti';
		},
	},
});
