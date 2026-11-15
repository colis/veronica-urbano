<?php
/**
 * VU Blocks Core Functionality
 *
 * @package VUBlocks\Core
 */

namespace VUBlocks\Core;

use VUCore\BaseAbstract;

/**
 * Class Core.
 */
class Core extends BaseAbstract {

	/**
	 * Activate the plugin
	 *
	 * @return void
	 */
	public function activation_hook(): void {
		do_action( 'vu_blocks_activated' );
	}

	/**
	 * Deactivate the plugin
	 *
	 * Uninstall routines should be in uninstall.php
	 *
	 * @return void
	 */
	public function deactivation_hook(): void {
		do_action( 'vu_blocks_deactivated' );
	}

	/**
	 * Registers the default textdomain.
	 *
	 * @return void
	 */
	public function i18n(): void {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'vu-blocks' );
		load_textdomain( 'vu-blocks', WP_LANG_DIR . '/plugins/vu-blocks-' . $locale . '.mo' );
		load_plugin_textdomain( 'vu-blocks', false, plugin_basename( VU_BLOCKS_PATH ) . '/languages/' );
	}

	/**
	 * Dequeue default block "theme" styles in the editor.
	 *
	 * These are not core block styles, but opinionated theme
	 * styles that are added as inline CSS within the block editor.
	 *
	 * @return void
	 */
	public function dequeue_core_block_theme_editor_styles(): void {
		wp_deregister_style( 'wp-block-library-theme' );
	}

	/**
	 * Register scripts and styles.
	 *
	 * @return void
	 */
	public function register_scripts_styles(): void {

		// jQuery script.
		wp_register_script(
			'jquery-cdn',
			'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js',
			[],
			'3.7.1',
			[
				'in_footer' => true,
			]
		);

		// jQuery UI script.
		wp_register_script(
			'jquery-ui-cdn',
			'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js',
			[ 'jquery-cdn' ],
			'1.14.1',
			[
				'in_footer' => true,
			]
		);

		// reCAPTCHA script.
		$recaptcha_site_key = get_field( 'recaptcha_site_key', 'option' );
		if ( ! empty( $recaptcha_site_key ) && ! is_front_page() && ! is_admin() ) {
			wp_register_script(
				'recaptcha',
				'https://www.google.com/recaptcha/api.js?render=' . esc_attr( $recaptcha_site_key ) . '&onload=onRecaptchaLoadCallback',
				[],
				'1.0.0',
				[
					'strategy'  => 'async',
					'in_footer' => true,
				]
			);
			wp_enqueue_script( 'recaptcha' );
		}
	}
}
