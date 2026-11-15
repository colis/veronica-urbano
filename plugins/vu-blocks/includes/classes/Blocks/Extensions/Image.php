<?php
/**
 * Image Class
 *
 * @package VUBlocks\Blocks\Extensions\Image
 */

namespace VUBlocks\Blocks\Extensions;

use VUBlocks\Traits\MobileImage;
use VUCore\BaseAbstract;
use VUCore\Core\Icon;
use WP_Block_Type_Registry;

/**
 * Class Image.
 */
class Image extends BaseAbstract {

	use MobileImage;

	/**
	 * Add a mobile image to the core/image block markup.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_image_insert_mobile_image( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		if ( empty( $block['attrs']['hasMobileImage'] ) || empty( $block['attrs']['mobileImage'] ) ) {
			return $content;
		}

		// Get the mobile image.
		$image_markup = $this->fetch_mobile_image( $block );

		// Add the mobile image to the block markup.
		if ( ! empty( $image_markup ) ) {
			$content = str_replace( '<img', $image_markup . '<img', $content );
		}

		return $content;
	}

	/**
	 * Register custom attributes to core/image block.
	 *
	 * @return void
	 */
	public function register_image_custom_attributes(): void {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $block ) {
			if ( 'core/image' === $block->name ) {
				$block->attributes = array_merge(
					$block->attributes,
					$this->get_mobile_image_attributes()
				);
			}
		}
	}
}
