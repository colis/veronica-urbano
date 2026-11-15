<?php
/**
 * VU Core List Class
 *
 * @package VUBlocks\Blocks\Extensions\ListBlock
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;

/**
 * Class ListBlock.
 */
class ListBlock extends BaseAbstract {

	/**
	 * Update the core/list block markup.
	 *
	 * @param string $content Block content.
	 *
	 * @return string
	 */
	public function core_list_update_markup( string $content ): string {

		if ( empty( $content ) ) {
			return '';
		}

		$content = str_replace( '<li>', '<li><span>', $content );
		$content = str_replace( '</li>', '</span></li>', $content );

		return $content;
	}
}
