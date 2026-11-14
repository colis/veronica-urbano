<?php
/**
 * VU Core
 *
 * @package VUCore
 */

namespace VUCore;

use VUCore\Acf\Acf;
use VUCore\Core\{Core, Performance};
use VUCore\Nav\Nav;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * VU Core Plugin main class.
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
	 * @throws NotFoundExceptionInterface On container not find class error.
	 */
	private function activation_hooks(): void {
		register_activation_hook( __FILE__, [ $this->app->get( Core::class ), 'activation_hook' ] );
		register_deactivation_hook( __FILE__, [ $this->app->get( Core::class ), 'deactivation_hook' ] );
	}

	/**
	 * Invoke all add_action, remove_action, do_action hooks in here.
	 *
	 * @return void
	 * @throws ContainerExceptionInterface On container error.
	 * @throws NotFoundExceptionInterface On container not find class error.
	 */
	private function actions(): void {

		// Taxonomies.
		foreach ( glob( __DIR__ . '/Taxonomy/*.php' ) as $cpt ) {
			$class_name = str_replace( '.php', '', pathinfo( $cpt, PATHINFO_BASENAME ) );
			if ( ! str_contains( $class_name, 'Abstract' ) ) {
				add_action( 'init', [ $this->app->get( 'VUCore\Taxonomy\\' . $class_name ), 'register' ] );
			}
		}

		// CPT.
		foreach ( glob( __DIR__ . '/PostType/*.php' ) as $cpt ) {
			$class_name = str_replace( '.php', '', pathinfo( $cpt, PATHINFO_BASENAME ) );
			if ( ! str_contains( $class_name, 'Abstract' ) ) {
				add_action( 'init', [ $this->app->get( 'VUCore\PostType\\' . $class_name ), 'register' ] );
			}
		}

		// Acf.
		add_action( 'acf/init', [ $this->app->get( Acf::class ), 'register_acf_local_fields' ] );

		// Load text domain.
		add_action( 'init', [ $this->app->get( Core::class ), 'i18n' ] );

		// Enqueue styles.
		add_action( 'admin_enqueue_scripts', [ $this->app->get( Core::class ), 'admin_styles_scripts' ] );

		// Register custom image sizes.
		add_action( 'after_setup_theme', [ $this->app->get( Core::class ), 'register_custom_image_sizes' ] );
		add_action( 'image_size_names_choose', [ $this->app->get( Core::class ), 'register_custom_image_sizes_names' ] );

		// Performance.
		// Disable Pings.
		add_action( 'schedule_event', [ $this->app->get( Performance::class ), 'disable_pings' ] );
		// Defer term counting when bulk editing.
		add_action( 'load-edit.php', [ $this->app->get( Performance::class ), 'defer_term_counting' ] );
		// Reduce fetch_feed timeout to 3s.
		add_action( 'wp_feed_options', [ $this->app->get( Performance::class ), 'wp_feed_options' ] );
		// Conditionally dequeue CSS & JS assets.
		add_action( 'wp_enqueue_scripts', [ $this->app->get( Performance::class ), 'dequeue_frontend_assets' ], PHP_INT_MAX );
		add_action( 'wp_default_styles', [ $this->app->get( Performance::class ), 'opt_out_block_library_inline_css' ], 999 );
		// Disable header meta/links.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'wp_head', 'rsd_link' );
		// Disable emojis.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		// Disable block directory assets.
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );

		// Nav Menus.
		add_action( 'after_setup_theme', [ $this->app->get( Nav::class ), 'register_nav_menus' ] );

		// Complete plugin loaded hook.
		do_action( 'vu_core_loaded' );
	}

	/**
	 * Invoke all add_filter, remove_filter, do_filter hooks in here.
	 *
	 * @return void
	 * @throws ContainerExceptionInterface On container error.
	 * @throws NotFoundExceptionInterface On container not find class error.
	 */
	private function filters(): void {

		// Attachment permalink.
		add_filter( 'attachment_link', [ $this->app->get( Core::class ), 'remove_attachment_permalink' ] );

		// Update allowed HTML attributes.
		add_filter( 'wp_kses_allowed_html', [ $this->app->get( Core::class ), 'update_wp_kses_allowed_html' ] );
		add_filter( 'kses_allowed_protocols', [ $this->app->get( Core::class ), 'update_kses_allowed_protocols' ] );
		add_filter( 'map_meta_cap', [ $this->app->get( Core::class ), 'allow_unfiltered_html_capability' ], 1, 2 );

		// Performance.
		add_filter( 'wp_theme_json_data_default', [ $this->app->get( Performance::class ), 'filter_theme_json_default_values' ] );
		add_filter( 'styles_inline_size_limit', [ $this->app->get( Performance::class ), 'limit_inline_style_size' ] );

		// Nav.
		add_filter( 'nav_menu_link_attributes', [ $this->app->get( Nav::class ), 'nav_menu_submenu_attrs' ], 10, 4 );
		add_filter( 'walker_nav_menu_start_el', [ $this->app->get( Nav::class ), 'nav_parent_menu_item_markup' ], 11, 4 );
	}
}
