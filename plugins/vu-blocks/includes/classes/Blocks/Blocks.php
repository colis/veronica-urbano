<?php
/**
 * Blocks Class
 *
 * @package VUBlocks\Blocks
 */

namespace VUBlocks\Blocks;

use VUCore\BaseAbstract;

/**
 * Class Blocks.
 */
class Blocks extends BaseAbstract {

	/**
	 * Register custom block types.
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		// Find all custom blocks and register them.
		wp_register_block_types_from_metadata_collection(
			VU_BLOCKS_PATH . 'dist',
			VU_BLOCKS_PATH . 'dist/blocks-manifest.php'
		);
	}

	/**
	 * Add a custom block category.
	 *
	 * @param array $categories Array of categories for block types.
	 *
	 * @return array
	 */
	public function register_block_category( array $categories ): array {
		return array_merge(
			[
				[
					'slug'  => 'vu-blocks',
					'title' => __( 'VU Blocks', 'vu-blocks' ),
				],
			],
			$categories
		);
	}
}
