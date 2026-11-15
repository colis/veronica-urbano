<?php
/**
 * MobileImage
 *
 * Shared block attributes for mobile image extension.
 *
 * @package VUBlocks\Traits
 */

namespace VUBlocks\Traits;

/**
 * Trait MobileImage.
 */
trait MobileImage {

	/**
	 * Get mobile image block attributes.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	protected function get_mobile_image_attributes(): array {
		return [
			'hasMobileImage' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'mobileImage'    => [
				'type' => 'string',
			],
		];
	}

	/**
	 * Fetch a mobile image for inclusion in block markup.
	 *
	 * @param array $block Block data.
	 *
	 * @return string
	 */
	protected function fetch_mobile_image( array $block ): string {
		$image_id     = wpcom_vip_attachment_url_to_postid( $block['attrs']['mobileImage'] );
		$size_slug    = $block['attrs']['sizeSlug'] ?? 'large';
		$image_markup = wp_get_attachment_image( $image_id, $size_slug, false, [ 'class' => "mobile wp-image-$image_id" ] );

		return $image_markup;
	}
}
