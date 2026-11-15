<?php
/**
 * Icon Preview admin page functionality.
 *
 * @package VUBlocks\Admin
 */

namespace VUBlocks\Admin;

use VUCore\BaseAbstract;
use VUCore\Core\Icon;

/**
 * Icon Preview admin class.
 */
class IconPreview extends BaseAbstract {

	/**
	 * Register admin scripts for the Icon Preview on the Site Options "Editors" page.
	 *
	 * @param string $hook_suffix The current admin page hook suffix.
	 *
	 * @return void
	 */
	public function register_scripts( string $hook_suffix ): void {
		if ( 'site-options_page_site-options-editors' !== $hook_suffix ) {
			return;
		}

		$asset_file = VU_BLOCKS_PATH . 'dist/_admin/icon-preview/index.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_script(
			'vu-blocks-icon-preview',
			VU_BLOCKS_URL . 'dist/_admin/icon-preview/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'vu-blocks-icon-preview',
			VU_BLOCKS_URL . 'dist/_admin/icon-preview/index.css',
			[ 'wp-components' ],
			$asset['version'],
		);

		// Provide icon data to the React app.
		$icons        = class_exists( Icon::class ) ? Icon::$vu_icons : [];
		$custom_icons = class_exists( Icon::class ) ? Icon::get_custom_icons() : [];

		wp_add_inline_script(
			'vu-blocks-icon-preview',
			'window.vuIconPreview = ' . wp_json_encode(
				[
					'icons' => array_merge( $icons, $custom_icons ),
				]
			) . ';',
			'before'
		);
	}
}
