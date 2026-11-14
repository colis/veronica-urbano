<?php
/**
 * Performance related updates.
 *
 * @package VUCore\Core
 */

namespace VUCore\Core;

use VUCore\BaseAbstract;
use SimplePie, WP_Styles, WP_Theme_JSON_Data, WP_Theme_JSON_Data_Gutenberg;

/**
 * Class Performance
 *
 * @package VUCore
 */
class Performance extends BaseAbstract {

	/**
	 * Reduce fetch_feed timeout to 3s.
	 *
	 * The default timeout for SimplePie is 10s; that is unnecessarily long.
	 * Let's keep things sane by lowering the timeout limit.
	 * No feeds should be really much slower than 1s anyway.
	 * If they are, you should find a different feed...
	 *
	 * @param SimplePie $feed RSS Feed object.
	 */
	public function wp_feed_options( SimplePie $feed ): void {
		$feed->set_timeout( 3 );
	}

	/**
	 * Bulk edits of lots of posts can trigger slow term count queries for each post updated.
	 *
	 * @return void
	 */
	public function defer_term_counting(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nonce is not available
		if ( isset( $_REQUEST['bulk_edit'] ) ) {
			wp_defer_term_counting( true );
			add_action(
				'shutdown',
				static function () {
					wp_defer_term_counting( false );
				}
			);
		}
	}

	/**
	 * Disable Pings.
	 *
	 * @param object|false $event Event object.
	 *
	 * @return object|false
	 */
	public function disable_pings( object|false $event ): object|false {

		// Already blocked, carry on.
		if ( ! is_object( $event ) ) {
			return $event;
		}

		if ( 'do_pings' === $event->hook ) {
			return false;
		}

		return $event;
	}

	/**
	 * Conditionally dequeue CSS stylesheets and JS scripts on the frontend.
	 *
	 * @return void
	 */
	public function dequeue_frontend_assets(): void {
		if ( is_admin() ) {
			return;
		}

		$styles_to_dequeue  = [
			// Dequeue block library common styles.
			// `/wp-includes/css/dist/block-library/common.min.css` stylesheet.
			'wp-block-library',
			// Dequeue default block "theme" styles.
			// These are not core block styles, but opinionated theme styles.
			'wp-block-gallery-theme',
			'wp-block-group-theme',
			'wp-block-image-theme',
			'wp-block-quote-theme',
			'wp-block-separator-theme',
			'wp-block-table-theme',
		];
		$scripts_to_dequeue = [];

		// If user is not logged in.
		if ( ! is_user_logged_in() ) {
			$scripts_to_dequeue[] = 'jquery';
			$scripts_to_dequeue[] = 'jquery-migrate';
		}

		$styles_to_dequeue  = apply_filters( 'vu_core_styles_to_dequeue', $styles_to_dequeue );
		$scripts_to_dequeue = apply_filters( 'vu_core_scripts_to_dequeue', $scripts_to_dequeue );

		// Dequeue the unused styles.
		foreach ( $styles_to_dequeue as $handle ) {
			wp_dequeue_style( $handle );
			wp_deregister_style( $handle );
		}

		// Keep wp-block-library registered as an empty handle so core styles
		// (e.g. block-style-variation-styles) satisfy dependency checks.
		if ( in_array( 'wp-block-library', $styles_to_dequeue, true ) ) {
			wp_register_style( 'wp-block-library', false, [], null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		}

		// Dequeue the unused scripts.
		foreach ( $scripts_to_dequeue as $handle ) {
			wp_dequeue_script( $handle );
			wp_deregister_script( $handle );
		}
	}

	/**
	 * Opt out of the wp-block-library common inline css.
	 * `/wp-includes/css/dist/block-library/common.min.css` stylesheet.
	 *
	 * By default this stylesheet is enqueued inline in the head above separate per-block styles.
	 * Once opted out, these styles will be enqueued as a <link> stylesheet which is also dequeued above.
	 *
	 * @param WP_Styles $wp_styles Styles registry instance.
	 *
	 * @return void
	 */
	public function opt_out_block_library_inline_css( WP_Styles $wp_styles ): void {
		if ( is_admin() ) {
			return;
		}

		$wp_styles->add_data( 'wp-block-library', 'path', false );
	}

	/**
	 * Filter default values from theme.json before rendering.
	 *
	 * @param WP_Theme_JSON_Data $theme_json Theme JSON object.
	 *
	 * @return WP_Theme_JSON_Data
	 */
	public function filter_theme_json_default_values( WP_Theme_JSON_Data $theme_json ): WP_Theme_JSON_Data {
		$data = $theme_json->get_data();

		// Colors & Shadows.
		$data['settings']['color']['duotone']['default']   = [];
		$data['settings']['color']['gradients']['default'] = [];
		$data['settings']['color']['palette']['default']   = [];
		$data['settings']['shadow']['presets']['default']  = [];

		// Font sizes.
		$data['settings']['typography']['fontSizes']['default'] = [];

		// Button block.
		unset( $data['settings']['blocks']['core/button']['dimensions']['width'] );
		unset( $data['settings']['blocks']['core/button']['dimensions']['dimensionSizes'] );
		unset( $data['styles']['blocks']['core/button']['variations'] );

		return new WP_Theme_JSON_Data( $data, 'default' );
	}

	/**
	 * Limit the inline style size to 50 KB.
	 *
	 * @param int $limit The inline style size limit.
	 *
	 * @return int The inline style size limit.
	 */
	public function limit_inline_style_size( int $limit ): int {
		return 50000;
	}
}
