<?php
/**
 * Extensions Class
 *
 * @package VUBlocks\Blocks
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;

/**
 * Class Extensions.
 */
class Extensions extends BaseAbstract {

	public const SCRIPT_HANDLE = 'vu-blocks-extensions';

	/**
	 * Register block extension script.
	 *
	 * @return void
	 */
	public function register_extensions() {
		$extension_assets = require VU_BLOCKS_PATH . 'dist/_extensions/index.asset.php';

		// Enqueue block extension script.
		wp_enqueue_script(
			self::SCRIPT_HANDLE,
			VU_BLOCKS_URL . 'dist/_extensions/index.js',
			$extension_assets['dependencies'],
			$extension_assets['version'],
			[]
		);
	}
}
