<?php
/**
 * VU Blocks Block Editor Functionality
 *
 * @package VUBlocks\Core
 */

namespace VUBlocks\Core;

use VUCore\BaseAbstract;
use VUCore\Core\Icon;

use WP_Block_Editor_Context, WP_Block_Type_Registry;

/**
 * Class BlockEditor.
 */
class BlockEditor extends BaseAbstract {

	/**
	 * Add/remove theme support for Gutenberg features.
	 *
	 * @return void
	 */
	public function register_gutenberg_theme_support(): void {

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove support for Core Block Patterns.
		remove_theme_support( 'core-block-patterns' );
	}

	/**
	 * Enqueue the block editor assets.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets(): void {

		if ( ! is_admin() ) {
			return;
		}

		$script_name = 'vu-blocks-scripts';

		$js_file  = 'dist/blocks-scripts.js';
		$css_file = 'dist/blocks-styles.css';

		$js_fileuri  = VU_BLOCKS_URL . $js_file;
		$css_fileuri = VU_BLOCKS_URL . $css_file;

		// Automatically load dependencies and version.
		$js_asset_file  = include VU_BLOCKS_PATH . 'dist/blocks-scripts.asset.php';
		$css_asset_file = include VU_BLOCKS_PATH . 'dist/blocks-styles.asset.php';

		wp_enqueue_script(
			$script_name,
			$js_fileuri,
			$js_asset_file['dependencies'],
			$js_asset_file['version'],
			true
		);

		// Icons.
		$vu_icons     = class_exists( Icon::class ) ? Icon::$vu_icons : [];
		$custom_icons = class_exists( Icon::class ) ? Icon::get_custom_icons() : [];

		wp_add_inline_script(
			$script_name,
			'const vuCore = ' . wp_json_encode(
				[
					'vuIcons' => array_merge( $vu_icons, $custom_icons ),
				]
			),
			'before',
		);

		wp_enqueue_style(
			'vu-blocks-styles',
			$css_fileuri,
			$css_asset_file['dependencies'],
			$css_asset_file['version']
		);
	}

	/**
	 * Update block editor settings.
	 *
	 * @param array $settings The block editor settings.
	 *
	 * @return array
	 */
	public function update_block_editor_settings( array $settings ): array {

		// Disable OpenVerse media category.
		$settings['enableOpenverseMediaCategory'] = false;

		// Disable content only editing for unsynced patterns.
		// @see https://make.wordpress.org/core/2026/03/15/pattern-editing-in-wordpress-7-0/.
		$settings['disableContentOnlyForUnsyncedPatterns'] = true;

		// Disable responsive styles.
		// @see https://make.wordpress.org/core/2026/08/05/responsive-block-styles-and-configurable-viewports-in-wordpress-7-1/.
		$settings['responsiveEditingEnabled'] = false;

		// Disable state styles.
		// @see https://make.wordpress.org/core/2026/08/05/pseudo-and-custom-style-states-in-wordpress-7-1/.
		$settings['blockStatesEditingEnabled'] = false;

		return $settings;
	}

	/**
	 * Filter the list of allowed block types.
	 *
	 * @param array|bool              $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
	 * @param WP_Block_Editor_Context $block_editor_context The current block editor context.
	 *
	 * @return array The filtered list of allowed block types.
	 */
	public function disallow_block_types( array|bool $allowed_block_types, WP_Block_Editor_Context $block_editor_context ): array {

		// Define disallowed blocks.
		$disallowed_blocks = [
			'core/accordion',
			'core/archives',
			'core/audio',
			'core/avatar',
			'core/breadcrumbs',
			'core/calendar',
			'core/categories',
			'core/code',
			'core/comments',
			'core/details',
			'core/embed',
			'core/file',
			'core/icon',
			'core/latest-comments',
			'core/latest-posts',
			'core/loginout',
			'core/math',
			'core/more',
			'core/navigation',
			'core/nextpage',
			'core/page-list',
			'core/playlist',
			'core/post-author',
			'core/post-author-biography',
			'core/post-author-name',
			'core/post-comments',
			'core/post-comments-count',
			'core/post-comments-form',
			'core/post-comments-link',
			'core/post-content',
			'core/post-date',
			'core/post-excerpt',
			'core/post-featured-image',
			'core/post-navigation-link',
			'core/post-terms',
			'core/post-time-to-read',
			'core/preformatted',
			'core/pullquote',
			'core/query-pagination',
			'core/query-title',
			'core/query-total',
			'core/read-more',
			'core/rss',
			'core/shortcode',
			'core/search',
			'core/site-logo',
			'core/site-tagline',
			'core/site-title',
			'core/social-links',
			'core/tabs',
			'core/tag-cloud',
			'core/template-part',
			'core/term-count',
			'core/term-description',
			'core/term-name',
			'core/terms-query',
			'core/verse',
			'core/video',
			'rank-math/faq-block',
			'rank-math/howto-block',
			'rank-math/rich-snippet',
			'rank-math/toc-block',
			'safe-svg/svg-icon',
		];

		// Get all registered blocks if $allowed_block_types is not already set.
		if ( ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
			$registered_blocks   = WP_Block_Type_Registry::get_instance()->get_all_registered();
			$allowed_block_types = array_keys( $registered_blocks );
		}

		// Create a new array for the allowed blocks.
		$filtered_blocks = [];

		// Loop through each block in the allowed blocks list.
		foreach ( $allowed_block_types as $block ) {

			// Check if the block is not in the disallowed blocks list.
			if ( ! in_array( $block, $disallowed_blocks, true ) ) {

				// If it's not disallowed, add it to the filtered list.
				$filtered_blocks[] = $block;
			}
		}

		// Return the filtered list of allowed blocks.
		return $filtered_blocks;
	}

	/**
	 * Disable fit text setting for specific blocks.
	 *
	 * @param array $metadata The block metadata.
	 *
	 * @return array The filtered block metadata.
	 */
	public function disable_block_fit_text_setting( array $metadata ): array {
		$blocks = [
			'core/paragraph',
			'core/heading',
		];

		if ( in_array( $metadata['name'], $blocks, true ) ) {
			$metadata['supports']['typography']['fitText'] = false;
		}

		return $metadata;
	}

	/**
	 * Update Patterns post type arguments.
	 *
	 * @param array  $args Array of arguments for registering a post type.
	 * @param string $post_type Post type key.
	 *
	 * @return array
	 */
	public function wp_block_update_post_type_args( $args, $post_type ): array {

		$args['supports'][] = 'author';
		$args['supports'][] = 'excerpt';

		return $args;
	}

	/**
	 * Update Pattern Category taxonomy arguments.
	 *
	 * @param array  $args     Array of arguments for registering a post type.
	 * @param string $taxonomy Taxonomy key.
	 *
	 * @return array
	 */
	public function wp_pattern_category_update_taxonomy_args( $args, $taxonomy ): array {

		if ( 'wp_pattern_category' === $taxonomy ) {
			$args['hierarchical'] = true;
		}

		return $args;
	}

	/**
	 * Add admin menu links for managing Patterns and Pattern categories.
	 *
	 * @return void
	 */
	public function add_patterns_admin_menu_items(): void {
		add_menu_page( __( 'Patterns', 'vu-blocks' ), __( 'Patterns', 'vu-blocks' ), 'edit_posts', 'site-editor.php?path=/patterns', '', 'dashicons-insert', 55 );
		add_submenu_page( 'site-editor.php?path=/patterns', __( 'All Patterns', 'vu-blocks' ), __( 'All Patterns', 'vu-blocks' ), 'edit_posts', 'site-editor.php?path=/patterns' );
		add_submenu_page( 'site-editor.php?path=/patterns', __( 'Pattern Categories', 'vu-blocks' ), __( 'Pattern Categories', 'vu-blocks' ), 'edit_posts', '/edit-tags.php?taxonomy=wp_pattern_category&post_type=wp_block' );
	}

	/**
	 * Register custom block pattern categories.
	 *
	 * @return void
	 */
	public function register_block_pattern_categories(): void {

		$categories = [
			__( 'Callouts', 'vu-blocks' ),
			__( 'Cards', 'vu-blocks' ),
			__( 'Carousels', 'vu-blocks' ),
			__( 'Columns', 'vu-blocks' ),
			__( 'Forms', 'vu-blocks' ),
			__( 'Heros', 'vu-blocks' ),
			__( 'Images', 'vu-blocks' ),
			__( 'Links', 'vu-blocks' ),
			__( 'Listicles', 'vu-blocks' ),
			__( 'Menu', 'vu-blocks' ),
			__( 'Quotes', 'vu-blocks' ),
			__( 'Statistics', 'vu-blocks' ),
			__( 'Stories', 'vu-blocks' ),
			__( 'Tabs', 'vu-blocks' ),
			__( 'Tiles', 'vu-blocks' ),
		];

		foreach ( $categories as $category ) {
			register_block_pattern_category(
				'vu-' . sanitize_title( $category ),
				[
					'label' => $category,
				],
			);
		}
	}
}
