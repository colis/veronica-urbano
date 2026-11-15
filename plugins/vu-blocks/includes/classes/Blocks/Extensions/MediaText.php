<?php
/**
 * MediaText Class
 *
 * @package VUBlocks\Blocks\Extensions\MediaText
 */

namespace VUBlocks\Blocks\Extensions;

use VUBlocks\Traits\MobileImage;
use VUCore\BaseAbstract;
use VUCore\Brightcove\Brightcove;
use WP_Block_Type_Registry, WP_HTML_Tag_Processor;

/**
 * Class MediaText.
 */
class MediaText extends BaseAbstract {

	use MobileImage;

	/**
	 * Adds 'correct' width & height values to img tags in core/media-text blocks
	 *
	 * @param string $block_content The block content.
	 *
	 * @return string Modified block content.
	 */
	public function core_media_text_fix_image_sizes( string $block_content ): string {

		// Don't fire in wp-admin.
		if ( is_admin() ) {
			return $block_content;
		}

		// If no HTML Tag Processor API, bail.
		if ( ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
			return $block_content;
		}

		// Check img tag.
		$img_tag_processor = new WP_HTML_Tag_Processor( $block_content );
		if ( ! $img_tag_processor->next_tag( [ 'tag_name' => 'img' ] ) ) {
			return $block_content;
		}

		// Get the image ID class from the img tag.
		$img_class = $img_tag_processor->get_attribute( 'class' );
		if ( ! $img_class || ! preg_match( '/wp-image-(\d+)/', $img_class, $image_matches ) ) {
			return $block_content;
		}
		$image_id = $image_matches[1];

		// Get the size name class from the img tag.
		$size_class = $img_tag_processor->get_attribute( 'class' );
		if ( ! $size_class || ! preg_match( '/size-([^\s"]+)/', $size_class, $size_matches ) ) {
			return $block_content;
		}
		$size_name = $size_matches[1];

		// If the size is full, bail.
		if ( 'full' === $size_name ) {
			return $block_content;
		}

		// Get meta for the attachment sizes.
		$metadata = wp_get_attachment_metadata( (int) $image_id );

		// If no size meta is available for this size, bail.
		if ( ! $metadata || empty( $metadata['sizes'][ $size_name ] ) ) {
			return $block_content;
		}

		// Get values ready.
		$width                = $metadata['sizes'][ $size_name ]['width'];
		$height               = $metadata['sizes'][ $size_name ]['height'];
		$update_tag_processor = new WP_HTML_Tag_Processor( $block_content );

		// Set width/height for img.
		if ( $update_tag_processor->next_tag( [ 'tag_name' => 'img' ] ) ) {
			$update_tag_processor->set_attribute( 'width', esc_attr( $width ) );
			$update_tag_processor->set_attribute( 'height', esc_attr( $height ) );
		}

		// Get the result ready.
		$block_content = $update_tag_processor->get_updated_html();

		return $block_content;
	}

	/**
	 * Add a mobile image to the core/media-text block markup.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_media_text_insert_mobile_image( string $content, array $block ): string {

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
			$content = str_replace( '<figure class="wp-block-media-text__media"><img', '<figure class="wp-block-media-text__media">' . $image_markup . '<img', $content );
		}

		return $content;
	}

	/**
	 * Register custom attributes to `core/media-text` block.
	 *
	 * @return void
	 */
	public function register_media_text_custom_attributes(): void {

		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $block ) {
			if ( 'core/media-text' === $block->name ) {
				$block->attributes = array_merge(
					$block->attributes,
					$this->get_mobile_image_attributes()
				);

				$block->attributes['minHeightDesktop'] = [
					'type'    => 'string',
					'default' => '',
				];
			}
		}
	}
}
