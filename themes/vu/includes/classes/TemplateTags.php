<?php
/**
 * Template Tag functionality
 *
 * @package VUTheme
 * @since 1.9.0
 */

namespace VUTheme;

/**
 * Class TemplateTags
 */
class TemplateTags {

	/**
	 * Output Project navigation component.
	 *
	 * @return void
	 */
	public function vu_theme_project_nav(): void {
		get_template_part( 'template-parts/project/nav' );
	}
}
