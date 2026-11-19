<?php
/**
 * VU Blocks
 * Post Filter Block - Render
 *
 * @package VUBlocks
 *
 * $attributes provides the query and query ID for backend rendering.
 * $block provides the query and query ID for frontend rendering.
 */

$scope = vu_blocks_block_render_scope( $attributes ?? null, $block ?? null, $content ?? null );
if ( null === $scope ) {
	return;
}

[ $attributes, $block, $content ] = $scope;

if ( ! isset( $attributes['queryId'] ) && ! isset( $block->context['queryId'] ) ) {
	return;
}

$current_taxonomy = 'category';

$category_terms = ! empty( $attributes['selectedFilters'] ) ? $attributes['selectedFilters'] : get_terms(
	[
		'taxonomy'   => $current_taxonomy,
		'hide_empty' => true,
	]
);

$terms = [
	[
		'id'   => 0,
		'name' => __( 'Tutti', 'vu-blocks' ),
		'slug' => 'tutti',
	],
	...$category_terms,
];

$query_id = $block->context['queryId'] ?? $attributes['queryId'];
$query    = $block->context['query'] ?? $attributes['query'];
$inherit  = ! empty( $query['inherit'] );
$page_var = $inherit ? "query-{$current_taxonomy}" : "query-{$query_id}-{$current_taxonomy}";

$store_name = 'post-filter-store';
$input_name = wp_unique_id( "post-filter-{$query_id}-" );

?>

<ul
	<?php echo wp_kses_data( get_block_wrapper_attributes() ); ?>
	<?php
	echo wp_kses_data(
		wp_interactivity_data_wp_context(
			[
				'filters'         => array_values( $terms ),
				'filterParameter' => $page_var,
				'inherit'         => $inherit,
				'inputName'       => $input_name,
				'queryId'         => $query_id,
				'selectedFilter'  => '',
				'taxonomy'        => $current_taxonomy,
			]
		)
	);
	?>
	data-wp-interactive="<?php echo esc_attr( $store_name ); ?>"
	data-wp-class--hidden="!context.filters.length"
	data-wp-init="callbacks.initFilters"
	data-wp-on-window--popstate="callbacks.initFilters"
	aria-label="<?php esc_attr_e( 'Filtra per categoria', 'vu-blocks' ); ?>"
>
	<template data-wp-each--filter="context.filters">
		<li>
			<input
				data-wp-bind--checked="state.isFilterChecked"
				data-wp-bind--name="context.inputName"
				data-wp-bind--id="state.filterId"
				data-wp-bind--value="context.filter.slug"
				data-wp-on--change="actions.onFilterChange"
				type="radio"
			>
			<label
				data-wp-bind--for="state.filterId"
				data-wp-text="context.filter.name"
			></label>
		</li>
	</template>
</ul>
