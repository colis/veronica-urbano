<?php
/**
 * Polaroid Class
 *
 * @package VUBlocks\Blocks\Extensions\Polaroid
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;
use WP_HTML_Tag_Processor;

/**
 * Class Polaroid.
 */
class Polaroid extends BaseAbstract {

	public const STORE     = 'polaroid-store';
	public const DIALOG_ID = 'vu-polaroid';

	/**
	 * Referenced media for the polaroid dialog.
	 *
	 * @var array<int, array>
	 */
	private array $images = [];

	/**
	 * Add viewer bindings to marked words, preserving the paragraph's own HTML.
	 *
	 * @param string $content Rendered Paragraph content.
	 *
	 * @return string
	 */
	public function core_paragraph_maybe_render_polaroid( string $content ): string {

		if ( is_admin() || ! str_contains( $content, 'data-polaroid-id' ) ) {
			return $content;
		}

		$html = new WP_HTML_Tag_Processor( $content );

		$query = [
			'tag_name'   => 'BUTTON',
			'class_name' => 'c-polaroid__trigger',
		];

		while ( $html->next_tag( $query ) ) {
			$reference = $html->get_attribute( 'data-polaroid-id' );

			if (
				! is_string( $reference ) ||
				! ctype_digit( $reference ) ||
				0 === (int) $reference
			) {
				continue;
			}

			$attachment_id = (int) $reference;

			if ( ! isset( $this->images[ $attachment_id ] ) ) {
				$image = wp_get_attachment_image_src( $attachment_id, 'large' );

				if ( ! $image ) {
					$html->set_attribute( 'disabled', true );
					continue;
				}

				$this->images[ $attachment_id ] = [
					'id'      => $attachment_id,
					'src'     => $image[0],
					'width'   => $image[1],
					'height'  => $image[2],
					'alt'     => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
					'srcset'  => wp_get_attachment_image_srcset( $attachment_id, 'large' ),
					'sizes'   => wp_get_attachment_image_sizes( $attachment_id, 'large' ),
					'caption' => wp_strip_all_tags( (string) wp_get_attachment_caption( $attachment_id ) ),
				];
			}

			$html->set_attribute( 'type', 'button' );
			$html->set_attribute( 'aria-haspopup', 'dialog' );
			$html->set_attribute( 'aria-controls', self::DIALOG_ID );
			$html->set_attribute( 'data-wp-interactive', self::STORE );
			$html->set_attribute(
				'data-wp-context',
				wp_json_encode( [ 'polaroidId' => $attachment_id ] )
			);
			$html->set_attribute( 'data-wp-on--click', 'actions.open' );
			$html->set_attribute( 'data-wp-bind--disabled', '!state.ready' );
			$html->set_attribute( 'disabled', true );
		}

		return $html->get_updated_html();
	}

	/**
	 * Render the polaroid outside the theme's layout containers when used.
	 *
	 * @return void
	 */
	public function render_polaroid_dialog(): void {

		if ( ! $this->images ) {
			return;
		}

		$asset = require VU_BLOCKS_PATH . 'dist/_extensions/polaroid/view.asset.php';

		wp_enqueue_script_module(
			'_extensions/polaroid-block',
			VU_BLOCKS_URL . 'dist/_extensions/polaroid/view.js',
			$asset['dependencies'],
			$asset['version']
		);

		ob_start();

		get_template_part(
			'template-parts/polaroid',
			null,
			[ 'images' => $this->images ]
		);

		$markup                         = ob_get_clean();
		$allowed                        = wp_kses_allowed_html( 'post' );
		$allowed['template']            = [ 'data-*' => true ];
		$allowed['button']['autofocus'] = true;

		echo wp_kses( wp_interactivity_process_directives( $markup ), $allowed );
	}
}
