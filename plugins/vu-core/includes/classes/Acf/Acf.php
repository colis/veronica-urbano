<?php
/**
 * ACF Functionality
 *
 * @package VUCore\Core
 */

namespace VUCore\Acf;

use VUCore\BaseAbstract;

/**
 * WordPress Core Functionality.
 */
class Acf extends BaseAbstract {

	/**
	 * Register variables.
	 */
	const META_KEY                        = 'vu_core_';
	const SITE_OPTIONS_NAME               = 'site-options';
	const SITE_OPTIONS_HEADER_FOOTER_NAME = self::SITE_OPTIONS_NAME . '-header-footer';
	const SITE_OPTIONS_CAPABILITY         = 'manage_options'; // Administrators and above.

	/**
	 * Registers a custom options page through ACF.
	 *
	 * @return void
	 */
	public function register_acf_settings_page(): void {

		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				[
					'page_title' => __( 'Site Options', 'vu-core' ),
					'menu_title' => __( 'Site Options', 'vu-core' ),
					'menu_slug'  => self::SITE_OPTIONS_NAME,
					'capability' => self::SITE_OPTIONS_CAPABILITY,
					'position'   => 59,
				]
			);

			acf_add_options_sub_page(
				[
					'page_title'  => __( 'Header & Footer', 'vu-core' ),
					'menu_title'  => __( 'Header & Footer', 'vu-core' ),
					'parent_slug' => self::SITE_OPTIONS_NAME,
					'menu_slug'   => self::SITE_OPTIONS_HEADER_FOOTER_NAME,
					'capability'  => self::SITE_OPTIONS_CAPABILITY,
				]
			);
		}
	}

	/**
	 * Registers ACF fields for site options, custom post types, taxonomies and blocks.
	 *
	 * @return void
	 */
	public function register_acf_local_fields(): void {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			foreach ( glob( __DIR__ . '/Fields/*.php' ) as $file ) {
				require_once $file;
			}
		}
	}
}
