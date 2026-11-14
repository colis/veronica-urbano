<?php
/**
 * Core Functionality
 *
 * @package VUCore\Core
 */

namespace VUCore\Core;

use VUCore\BaseAbstract;
use WP_Error;

/**
 * WordPress Core Functionality.
 */
class Core extends BaseAbstract {

	/**
	 * Script and Style enqueue contexts allowed.
	 */
	private const ENQUEUE_CONTEXTS = [ 'admin', 'frontend' ];

	/**
	 * Activate the plugin
	 *
	 * @return void
	 */
	public function activation_hook(): void {
		do_action( 'vu_core_activated' );
	}

	/**
	 * Deactivate the plugin
	 *
	 * Uninstall routines should be in uninstall.php
	 *
	 * @return void
	 */
	public function deactivation_hook(): void {
		do_action( 'vu_core_deactivated' );
	}

	/**
	 * Registers the default textdomain.
	 *
	 * @return void
	 */
	public function i18n(): void {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'vu' );
		load_textdomain( 'vu-core', WP_LANG_DIR . '/plugins/vu-core-' . $locale . '.mo' );
		load_plugin_textdomain( 'vu-core', false, plugin_basename( VU_CORE_PATH ) . '/languages/' );
	}

	/**
	 * Generate an URL to a stylesheet.
	 *
	 * @param string $stylesheet Stylesheet file name (no .css extension).
	 * @param string $context    Context for the script ('admin' or 'frontend').
	 *
	 * @return string|WP_Error URL
	 */
	public function style_url( string $stylesheet, string $context ): string|WP_Error {
		if ( ! in_array( $context, self::ENQUEUE_CONTEXTS, true ) ) {
			return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in VUCore stylesheet loader.' );
		}

		return VU_CORE_URL . "dist/{$stylesheet}.css";
	}

	/**
	 * Generate an URL to a script.
	 *
	 * @param string $script  Script file name (no .js extension).
	 * @param string $context Context for the script ('admin' or 'frontend').
	 *
	 * @return string|WP_Error URL
	 */
	public function script_url( string $script, string $context ): string|WP_Error {
		if ( ! in_array( $context, self::ENQUEUE_CONTEXTS, true ) ) {
			return new WP_Error( 'invalid_enqueue_context', 'Invalid $context specified in VUCore script loader.' );
		}

		return VU_CORE_URL . "dist/{$script}.js";
	}

	/**
	 * Enqueue styles and scripts for admin.
	 *
	 * @return void
	 */
	public function admin_styles_scripts(): void {

		wp_enqueue_style(
			'vu-core-admin',
			$this->style_url( 'admin-styles', 'admin' ),
			[],
			VU_CORE_VERSION
		);

		wp_enqueue_script(
			'vu-core-admin',
			$this->script_url( 'admin-scripts', 'admin' ),
			[ 'jquery' ],
			VU_CORE_VERSION,
			true
		);
	}

	/**
	 * Register custom image sizes.
	 *
	 * @return void
	 */
	public function register_custom_image_sizes(): void {
		add_image_size( 'xx-large', 2000 );
		add_image_size( 'container', 1312 );
		add_image_size( 'x-large', 1400 );
		add_image_size( 'card-thumbnail', 744 );
		add_image_size( 'mobile', 480 );
		add_image_size( 'menu-thumbnail', 100 );
	}

	/**
	 * Register custom image size names for the media selector.
	 *
	 * @param array $sizes Array of image sizes.
	 *
	 * @return array
	 */
	public function register_custom_image_sizes_names( array $sizes ): array {
		return array_merge(
			$sizes,
			[
				'thumbnail'      => __( 'Thumbnail (150px sq.)', 'vu-core' ),
				'medium'         => __( 'Medium (300px)', 'vu-core' ),
				'mobile'         => __( 'Mobile (480px)', 'vu-core' ),
				'card-thumbnail' => __( 'Card Thumbnail (744px)', 'vu-core' ),
				'large'          => __( 'Large (1024px)', 'vu-core' ),
				'container'      => __( 'Container (1312px)', 'vu-core' ),
				'xx-large'       => __( '2-X Large (2000px)', 'vu-core' ),
			],
		);
	}

	/**
	 * Remove the attachment permalink URL.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/attachment_link/
	 *
	 * @param string $link The attachment link.
	 *
	 * @return string
	 */
	public function remove_attachment_permalink( string $link ): string {
		return '';
	}

	/**
	 * Update the wp_kses allowed HTML attributes.
	 *
	 * @param array $tags Allowed tags, attributes, and/or entities.
	 *
	 * @return array
	 */
	public function update_wp_kses_allowed_html( array $tags ): array {
		$tags['img']['sizes']  = true;
		$tags['img']['srcset'] = true;
		$tags['a']['tabindex'] = true;
		return $tags;
	}

	/**
	 * Update the kses allowed protocols.
	 *
	 * @param array $protocols Allowed protocols.
	 *
	 * @return array
	 */
	public function update_kses_allowed_protocols( array $protocols ): array {
		$protocols[] = 'data';
		return $protocols;
	}

	/**
	 * Allow the "unfiltered_html" capability for admins and editors.
	 *
	 * @param array  $caps Primitive capabilities required of the user.
	 * @param string $cap  Capability being checked.
	 *
	 * @return array
	 */
	public function allow_unfiltered_html_capability( array $caps, string $cap ): array {
		if ( 'unfiltered_html' === $cap && current_user_can( 'edit_pages' ) ) {
			$caps = [ $cap ];
		}

		return $caps;
	}
}
