<?php
/**
 * VU Blocks
 *
 * @package VUBlocks
 */

namespace VUBlocks;

use VUBlocks\Admin\IconPreview;
use VUBlocks\Blocks\Blocks;
use VUBlocks\Blocks\PostFilter\PostFilter;
use VUBlocks\Blocks\Extensions\{
	Button,
	Cover,
	Extensions,
	Image,
	ListBlock,
	MediaText,
	Paragraph,
	Separator,
};
use VUBlocks\Core\{BlockEditor, Core};
use VUCore\BaseAbstract;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Plugin main class.
 */
class Plugin extends BaseAbstract {

	/**
	 * Set up the container deps and fire off all WordPress hooks used in this plugin.
	 */
	public function register(): void {
		try {
			$this->activation_hooks();
			$this->actions();
			$this->filters();
		} catch ( Exception $e ) {
			echo esc_html( $e->getMessage() );
		}
	}

	/**
	 * Plugin activation and deactivation hooks.
	 *
	 * @return void
	 * @throws ContainerExceptionInterface On container error.
	 * @throws NotFoundExceptionInterface On Class not found error.
	 */
	private function activation_hooks(): void {
		register_activation_hook( __FILE__, [ $this->app->get( Core::class ), 'activation_hook' ] );
		register_deactivation_hook( __FILE__, [ $this->app->get( Core::class ), 'deactivation_hook' ] );
	}

	/**
	 * Invoke all add_action, remove_action, do_action hooks in here.
	 *
	 * @return void
	 */
	private function actions(): void {

		// Block Editor.
		// Gutenberg Theme support.
		add_action( 'after_setup_theme', [ $this->app->get( BlockEditor::class ), 'register_gutenberg_theme_support' ] );
		// Enqueue admin block editor assets.
		add_action( 'enqueue_block_assets', [ $this->app->get( BlockEditor::class ), 'enqueue_editor_assets' ] );
		// Patterns admin menu item.
		add_action( 'admin_menu', [ $this->app->get( BlockEditor::class ), 'add_patterns_admin_menu_items' ] );
		// Register custom block pattern categories.
		add_action( 'init', [ $this->app->get( BlockEditor::class ), 'register_block_pattern_categories' ] );

		// Blocks.
		add_action( 'init', [ $this->app->get( Blocks::class ), 'register_blocks' ] );
		add_action( 'block_categories_all', [ $this->app->get( Blocks::class ), 'register_block_category' ] );

		// Core.
		add_action( 'init', [ $this->app->get( Core::class ), 'i18n' ] );
		add_action( 'enqueue_block_editor_assets', [ $this->app->get( Core::class ), 'dequeue_core_block_theme_editor_styles' ], PHP_INT_MAX );
		add_action( 'enqueue_block_assets', [ $this->app->get( Core::class ), 'register_scripts_styles' ] );
		add_action( 'enqueue_block_assets', [ $this->app->get( Core::class ), 'register_modules' ] );

		// Block Extensions.
		add_action( 'enqueue_block_editor_assets', [ $this->app->get( Extensions::class ), 'register_extensions' ] );

		// Post Blocks.
		add_action( 'admin_enqueue_scripts', [ $this->app->get( IconPreview::class ), 'register_scripts' ] );

		// Post Filter block.
		add_action( 'pre_get_posts', [ $this->app->get( PostFilter::class ), 'process_post_filter_url_params' ] );

		do_action( 'vu_blocks_loaded' );
	}

	/**
	 * Invoke all add_filter, remove_filter, do_filter hooks in here.
	 *
	 * @return void
	 */
	private function filters(): void {

		// Block Editor.
		// Update block editor settings.
		add_filter( 'block_editor_settings_all', [ $this->app->get( BlockEditor::class ), 'update_block_editor_settings' ] );
		// Diallow block types.
		add_filter( 'allowed_block_types_all', [ $this->app->get( BlockEditor::class ), 'disallow_block_types' ], 10, 2 );
		// Disable fit text.
		add_filter( 'block_type_metadata', [ $this->app->get( BlockEditor::class ), 'disable_block_fit_text_setting' ] );
		// Update Patterns post type args and Patterns Category taxonomy args.
		add_filter( 'register_wp_block_post_type_args', [ $this->app->get( BlockEditor::class ), 'wp_block_update_post_type_args' ], 10, 2 );
		add_filter( 'register_taxonomy_args', [ $this->app->get( BlockEditor::class ), 'wp_pattern_category_update_taxonomy_args' ], 10, 2 );

		// Post Filter block.
		add_filter( 'query_loop_block_query_vars', [ $this->app->get( PostFilter::class ), 'filter_query_loop_block_query_vars' ], 10, 2 );

		// Block Extensions.
		// Button - order of filter execution is important.
		add_filter( 'render_block_core/button', [ $this->app->get( Button::class ), 'core_button_wrap_label' ], 10, 2 );
		add_filter( 'render_block_core/button', [ $this->app->get( Button::class ), 'core_button_maybe_insert_aria_label' ], 10, 2 );
		add_filter( 'render_block_core/button', [ $this->app->get( Button::class ), 'core_button_placeholder_button' ], 10, 2 );
		// Cover.
		add_filter( 'render_block_core/cover', [ $this->app->get( Cover::class ), 'core_cover_insert_mobile_image' ], 10, 2 );
		// Image.
		add_filter( 'render_block_core/image', [ $this->app->get( Image::class ), 'core_image_insert_mobile_image' ], 10, 2 );
		// List.
		add_filter( 'render_block_core/list', [ $this->app->get( ListBlock::class ), 'core_list_update_markup' ] );
		// Paragraph.
		add_filter( 'render_block_core/paragraph', [ $this->app->get( Paragraph::class ), 'core_paragraph_maybe_wrap_text' ], 10, 2 );
		// Media & Text.
		add_filter( 'render_block_core/media-text', [ $this->app->get( MediaText::class ), 'core_media_text_fix_image_sizes' ] );
		add_filter( 'render_block_core/media-text', [ $this->app->get( MediaText::class ), 'core_media_text_insert_mobile_image' ], 10, 2 );
		// Separator.
		add_filter( 'render_block_core/separator', [ $this->app->get( Separator::class ), 'core_separator_update_markup' ] );
	}
}
