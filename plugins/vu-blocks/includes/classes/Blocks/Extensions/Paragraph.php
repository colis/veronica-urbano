<?php
/**
 * Paragraph Class
 *
 * @package VUBlocks\Blocks\Extensions\Paragraph
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;
use WP_Block_Type_Registry;

/**
 * Class Paragraph.
 */
class Paragraph extends BaseAbstract {

	/**
	 * Register custom attributes to core/paragraph block.
	 *
	 * @return void
	 */
	public function register_paragraph_custom_attributes(): void {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $block ) {
			if ( 'core/paragraph' === $block->name ) {
				$block->attributes['hasLeadingIcon'] = [
					'type'    => 'boolean',
					'default' => false,
				];
				$block->attributes['icon']           = [
					'type'    => 'string',
					'default' => '',
				];
				$block->attributes['iconSize']       = [
					'type'    => 'string',
					'default' => '',
				];
				$block->attributes['iconGap']        = [
					'type'    => 'string',
					'default' => '',
				];
			}
		}
	}

	/**
	 * Prep the `core/paragraph` block for an icon by wrapping the text.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_paragraph_maybe_wrap_text( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		if ( ! $this->paragraph_has_icon_style( $block ) ) {
			return $content;
		}

		$has_leading_icon = $block['attrs']['hasLeadingIcon'] ?? false;
		$icon             = $block['attrs']['icon'] ?? '';

		if ( empty( $has_leading_icon ) || empty( $icon ) ) {
			return $content;
		}

		// Wrap the text in <span> tag for better styling control.
		$pos = strpos( $content, '">' );
		if ( false !== $pos ) {

			// Add opening <span> tag.
			$content = substr_replace( $content, '"><span class="label">', $pos, 2 );

			// Add closing </span> tag.
			$close_p_pos = strpos( $content, '</p>', $pos );
			if ( false !== $close_p_pos ) {
				$content = substr_replace( $content, '</span>', $close_p_pos, 0 );
			}
		}

		// Return content - SVG icon is added as a CSS pseudo-element.
		return $content;
	}

	/**
	 * Check whether the paragraph block style supports icons.
	 *
	 * @param array $block Block data.
	 *
	 * @return bool
	 */
	private function paragraph_has_icon_style( array $block ): bool {
		$class_name = $block['attrs']['className'] ?? '';

		if ( empty( $class_name ) ) {
			return false;
		}

		$classes = preg_split( '/\s+/', trim( $class_name ) );

		if ( empty( $classes ) ) {
			return false;
		}

		return in_array( 'is-style-eyebrow', $classes, true );
	}
}
