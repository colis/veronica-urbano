<?php
/**
 * VU Core Separator Class
 *
 * @package VUBlocks\Blocks\Extensions\Separator
 */

namespace VUBlocks\Blocks\Extensions;

use VUCore\BaseAbstract;

/**
 * Class Separator.
 */
class Separator extends BaseAbstract {

	/**
	 * Update the core/separator block markup.
	 *
	 * @param string $content Block content.
	 *
	 * @return string
	 */
	public function core_separator_update_markup( string $content ): string {

		if ( empty( $content ) ) {
			return '';
		}

		$content = str_replace( '<hr', '<hr aria-hidden="true"', $content );

		return $content;
	}
}
