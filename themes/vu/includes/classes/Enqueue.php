<?php
/**
 * Enqueue functionality
 *
 * @package VUTheme
 * @since 1.9.0
 */

namespace VUTheme;

use VUCore\Core\Core;

/**
 * Class Enqueue
 */
class Enqueue {

	/**
	 * Whether theme CSS should load via the late-print path (hoisted to the end of HEAD).
	 *
	 * @return bool
	 */
	private function vu_theme_should_hoist_frontend_styles(): bool {
		if ( is_admin() ) {
			return false;
		}

		if ( function_exists( 'wp_should_output_buffer_template_for_enhancement' )
			&& ! wp_should_output_buffer_template_for_enhancement() ) {
			return false;
		}

		return wp_should_load_separate_core_block_assets();
	}

	/**
	 * Register frontend styles. Enqueue in the head unless block-style hoisting is active.
	 *
	 * @return void
	 */
	public function vu_theme_frontend_styles(): void {
		$hoist_theme_styles = $this->vu_theme_should_hoist_frontend_styles();

		wp_register_style(
			'vu-styles',
			VU_THEME_DIST_URL . 'frontend.css',
			[],
			VU_THEME_VERSION,
			'all'
		);

		if ( ! $hoist_theme_styles ) {
			wp_enqueue_style( 'vu-styles' );
		}
	}

	/**
	 * Enqueue theme CSS late so hoisted block styles stay before these styles in HEAD.
	 *
	 * @return void
	 */
	public function vu_theme_enqueue_frontend_styles_late(): void {
		if ( ! $this->vu_theme_should_hoist_frontend_styles() ) {
			return;
		}

		wp_enqueue_style( 'vu-styles' );
	}

	/**
	 * Enqueue frontend scripts.
	 *
	 * @return void
	 */
	public function vu_theme_frontend_scripts(): void {

		$post_id = get_the_ID();

		// Theme scripts.
		wp_register_script(
			'vu-scripts',
			VU_THEME_DIST_URL . 'frontend.js',
			[ 'wp-i18n' ],
			VU_THEME_VERSION,
			[
				'strategy'  => 'defer',
				'in_footer' => true,
			]
		);
		wp_enqueue_script( 'vu-scripts' );

		// Localize site data.
		$localized_data         = [];
		$localized_data['site'] = [
			'ajaxURL' => admin_url( 'admin-ajax.php' ),
			'url'     => get_site_url(),
		];

		// Localize script data.
		wp_add_inline_script(
			'vu-scripts',
			'const vu = ' . wp_json_encode(
				$localized_data,
			),
			'before',
		);
	}

	/**
	 * Enqueue backend scripts.
	 *
	 * @return void
	 */
	public function vu_theme_backend_scripts(): void {

		wp_register_script(
			'vu-admin-scripts',
			VU_THEME_DIST_URL . 'admin.js',
			[],
			VU_THEME_VERSION,
			[
				'strategy'  => 'defer',
				'in_footer' => true,
			]
		);
		wp_enqueue_script( 'vu-admin-scripts' );
	}

	/**
	 * Enqueue backend admin styles.
	 *
	 * @return void
	 */
	public function vu_theme_editor_styles(): void {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'dist/admin.css' );
	}

	/**
	 * Output the es-module-shims polyfill for browsers that lack native import map support (e.g. Safari < 16.4).
	 *
	 * In polyfill mode the library is inert on browsers that already support import maps,
	 * so there is no runtime cost for modern user-agents.
	 *
	 * @link https://github.com/guybedford/es-module-shims
	 *
	 * @return void
	 */
	public function vu_theme_importmap_polyfill(): void {
		if ( is_admin() ) {
			return;
		}
		?>
		<script async src="https://ga.jspm.io/npm:es-module-shims@2.1.0/dist/es-module-shims.js"></script> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
		<?php
	}

	/**
	 * Add preload / pre-connect / dns-prefetch requests for assets.
	 *
	 * @return void
	 */
	public function vu_theme_preload_requests(): void {
		?>
		<link rel="preload" href="<?php echo esc_url( VU_THEME_DIST_URL . 'fonts/archivo-extrabold.6137cbe0.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="<?php echo esc_url( VU_THEME_DIST_URL . 'fonts/instrument-sans-regular.e6aa1b84.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
		<?php
	}

	/**
	 * Enqueue favicons.
	 *
	 * @return void
	 */
	public function vu_theme_favicons(): void {
		?>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( VU_THEME_URL ); ?>favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( VU_THEME_URL ); ?>favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( VU_THEME_URL ); ?>favicon/favicon-16x16.png">
		<link rel="manifest" href="<?php echo esc_url( VU_THEME_URL ); ?>favicon/site.webmanifest">
		<link rel="mask-icon" href="<?php echo esc_url( VU_THEME_URL ); ?>favicon/safari-pinned-tab.svg" color="#ffffff">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#ffffff">
		<meta name="msapplication-config" content="<?php echo esc_url( VU_THEME_URL ); ?>favicon/browserconfig.xml">
		<?php
	}
}
