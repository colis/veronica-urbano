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
	const META_KEY = 'vu_core_';

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
