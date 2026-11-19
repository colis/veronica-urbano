<?php
/**
 * Post Filter block functionality
 *
 * @package VUBlocks\Blocks\PostFilter
 */

namespace VUBlocks\Blocks\PostFilter;

use VUCore\BaseAbstract;
use WP_Block;
use WP_Query;

/**
 * Class PostFilter.
 */
class PostFilter extends BaseAbstract {
	/**
	 * Filters the arguments which will be passed to WP_Query for the Query Loop Block.
	 *
	 * @param array    $query Array containing parameters for WP_Query as parsed by the block context.
	 * @param WP_Block $block Block instance.
	 *
	 * @return array
	 */
	public function filter_query_loop_block_query_vars( array $query, WP_Block $block ): array {
		if ( isset( $block->context['queryId'] ) ) {
			$query['query_id'] = $block->context['queryId'];
		}

		return $query;
	}

	/**
	 * Take the post filter parameters from the URL and update the query vars.
	 *
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return void
	 */
	public function process_post_filter_url_params( WP_Query $query ): void {
		$query_id = $query->get( 'query_id', null );

		if ( ! $query->is_main_query() && is_null( $query_id ) ) {
			return;
		}

		$prefix     = $query->is_main_query() ? 'query-' : "query-{$query_id}-";
		$tax_query  = [];
		$valid_keys = [
			'order',
			'orderby',
		];

		// Map get params to this query.
		foreach ( $_GET as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( strpos( $key, $prefix ) === 0 ) {
				$key   = str_replace( $prefix, '', $key );
				$value = sanitize_text_field( urldecode( wp_unslash( $value ) ) );

				// Handle taxonomies specifically.
				if ( get_taxonomy( $key ) ) {
					// Allow multiple values by using array format for taxonomies.
					$value = array_map(
						'sanitize_text_field',
						array_map(
							'urldecode',
							explode( ',', wp_unslash( $value ) )
						)
					);

					$tax_query['relation'] = 'AND';
					$tax_query[]           = [
						'taxonomy' => $key,
						'terms'    => $value,
						'field'    => 'slug',
					];
				} else {
					// Other options should map directly to query vars.
					$key = sanitize_key( $key );

					if ( ! in_array( $key, $valid_keys, true ) ) {
						continue;
					}

					$query->set(
						$key,
						$value
					);
				}
			}
		}

		if ( ! empty( $tax_query ) ) {
			$existing_query = $query->get( 'tax_query', [] );

			if ( ! empty( $existing_query ) ) {
				$tax_query = [
					'relation' => 'AND',
					[ $existing_query ],
					$tax_query,
				];
			}

			$query->set( 'tax_query', $tax_query );
		}
	}
}
