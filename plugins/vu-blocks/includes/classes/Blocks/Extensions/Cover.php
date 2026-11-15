<?php
/**
 * Cover Class
 *
 * @package VUBlocks\Blocks\Extensions\Cover
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;
use WP_Block_Type_Registry;

/**
 * Class Cover.
 */
class Cover extends BaseAbstract {

	/**
	 * Add a mobile image to the core/cover block markup.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_cover_insert_mobile_image( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		if ( empty( $block['attrs']['hasMobileImage'] ) || empty( $block['attrs']['mobileImage'] ) ) {
			return $content;
		}

		// Get the mobile image.
		$image_id     = wpcom_vip_attachment_url_to_postid( $block['attrs']['mobileImage'] );
		$size_slug    = $block['attrs']['sizeSlug'] ?? 'large';
		$image_markup = wp_get_attachment_image( $image_id, $size_slug, false, [ 'class' => "wp-block-cover__image-background wp-block-cover__image-background--mobile wp-image-$image_id" ] );

		// Add the mobile image to the block markup.
		$content = str_replace( '<img', $image_markup . '<img', $content );

		return $content;
	}

	/**
	 * Register custom attributes to core/cover block.
	 *
	 * @return void
	 */
	public function register_cover_custom_attributes(): void {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $block ) {
			if ( 'core/cover' === $block->name ) {
				$block->attributes['hasMobileImage'] = [
					'type'    => 'boolean',
					'default' => false,
				];
				$block->attributes['mobileImage']    = [
					'type' => 'string',
				];
			}
		}
	}
}
