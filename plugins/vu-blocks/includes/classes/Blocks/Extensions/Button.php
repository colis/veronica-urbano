<?php
/**
 * Button Class
 *
 * @package VUBlocks\Blocks\Extensions\Button
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;
use WP_Block_Type_Registry, WP_HTML_Tag_Processor;

/**
 * Class Button.
 */
class Button extends BaseAbstract {

	/**
	 * Convert <a> to placeholder <div> tag if no `href` attribute is set.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_button_placeholder_button( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		$button = new WP_HTML_Tag_Processor( $content );

		// Perform a tag lookup.
		$query = [
			'tag_name' => 'A',
		];

		if ( ! $button->next_tag( $query ) ) {
			return $content;
		}

		$href = $button->get_attribute( 'href' );

		// If href attribute is empty and the button is neither a dialog trigger
		// nor a dialog closer, replace <a> tag with <div> tag so the
		// placeholder button still renders.
		if (
			empty( $href ) &&
			empty( $block['attrs']['triggerDialog'] ) &&
			empty( $block['attrs']['closesDialog'] )
		) {
			$content = str_replace( [ '<a ', '</a>' ], [ '<div ', '</div>' ], $content );
		}

		return $content;
	}

	/**
	 * Add screen reader label to `core/button` block.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_button_maybe_insert_aria_label( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		$text = $block['attrs']['accessibleText'] ?? false;
		$text = $text ? strval( $text ) : '';

		if ( empty( $text ) ) {
			return $content;
		}

		if ( str_contains( $content, 'aria-label' ) ) {
			return $content;
		}

		$button = new WP_HTML_Tag_Processor( $content );

		// Perform a tag lookup.
		$query = [
			'tag_name' => 'A',
		];

		if ( ! $button->next_tag( $query ) ) {
			return $content;
		}

		$button->set_attribute( 'aria-label', $text );

		return $button->get_updated_html();
	}

	/**
	 * Wrap the button label in <span> tag for better styling control.
	 *
	 * @param string $content Block content.
	 * @param array  $block   Block data.
	 *
	 * @return string
	 */
	public function core_button_wrap_label( string $content, array $block ): string {

		if ( empty( $content ) ) {
			return '';
		}

		// Find the position of the second instance of '">' (nested).
		$pos = strpos( $content, '">', strpos( $content, '">' ) + 1 );

		if ( false !== $pos ) {
			// Add opening <span> tag.
			$content = substr_replace( $content, '"><span class="label">', $pos, 2 );

			// Find the position of the closing tag of the inner wrapper.
			$close_pos = strpos( $content, '</a>', $pos );

			if ( false !== $close_pos ) {
				// Add closing </span> tag before the inner wrapper closes.
				$content = substr_replace( $content, '</span>', $close_pos, 0 );
			}
		}

		return $content;
	}

	/**
	 * Register custom attributes to `core/button` block.
	 *
	 * @return void
	 */
	public function register_button_custom_attributes(): void {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $block ) {
			if ( 'core/button' === $block->name ) {
				$block->attributes['accessibleText']  = [
					'type'    => 'string',
					'default' => '',
				];
				$block->attributes['hasLeadingIcon']  = [
					'type'    => 'boolean',
					'default' => false,
				];
				$block->attributes['hasMinWidth']     = [
					'type'    => 'boolean',
					'default' => false,
				];
				$block->attributes['hasTrailingIcon'] = [
					'type'    => 'boolean',
					'default' => false,
				];
				$block->attributes['icon']            = [
					'type'    => 'string',
					'default' => '',
				];
				$block->attributes['iconSize']        = [
					'type'    => 'string',
					'default' => '',
				];
				$block->attributes['iconGap']         = [
					'type'    => 'string',
					'default' => '',
				];
			}
		}
	}
}
