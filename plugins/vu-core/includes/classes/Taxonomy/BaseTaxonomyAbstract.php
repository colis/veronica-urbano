<?php
/**
 * Base class that all Taxonomies should extend from.
 *
 * @package VUCore\Taxonomy
 */

namespace VUCore\Taxonomy;

use VUCore\BaseAbstract;
use function register_extended_taxonomy;

/**
 * BaseTaxonomyAbstract Abstract Class.
 *
 * @package VUCore\Taxonomy
 */
abstract class BaseTaxonomyAbstract extends BaseAbstract {

	public const POST_TYPES         = [];
	public const PUBLIC             = false;
	public const PUBLICLY_QUERYABLE = true;
	public const SHOW_IN_MENU       = true;
	public const SHOW_IN_QUICK_EDIT = true;
	public const SHOW_UI            = true;

	/**
	 * Register the custom taxonomy.
	 */
	public function register(): void {

		$current_tax = get_class( $this );

		$args = wp_parse_args(
			$this->get_arguments(),
			[
				'labels'             => $this->get_labels(),
				'description'        => $this->get_description(),
				'hierarchical'       => true,
				'public'             => $current_tax::PUBLIC,
				'publicly_queryable' => $current_tax::PUBLICLY_QUERYABLE,
				'show_ui'            => $current_tax::SHOW_UI,
				'show_admin_column'  => true,
				'show_in_menu'       => $current_tax::SHOW_IN_MENU,
				'show_in_nav_menus'  => false,
				'show_in_quick_edit' => $current_tax::SHOW_IN_QUICK_EDIT,
				'show_in_rest'       => true,
				'show_tagcloud'      => false,
				'capabilities'       => $this->get_capabilities(),
				'admin_filters'      => $this->get_admin_filters(),
				'admin_cols'         => $this->get_admin_cols(),
			]
		);

		register_extended_taxonomy( $current_tax::NAME, $current_tax::POST_TYPES, $args );

		// Setup any default terms if necessary.
		$this->create_default_terms();
	}

	/**
	 * Return the labels for the taxonomy.
	 *
	 * @return array
	 */
	protected function get_labels(): array {
		return [];
	}

	/**
	 * Return the description for the taxonomy.
	 *
	 * @return string
	 */
	protected function get_description(): string {
		return '';
	}

	/**
	 * Get the arguments that configure the custom taxonomy.
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments(): array {
		return [];
	}

	/**
	 * Get the capabilities for the taxonomy.
	 *
	 * @return array
	 */
	protected function get_capabilities(): array {
		return [];
	}

	/**
	 * Set any default terms for the taxonomy.
	 *
	 * @return void
	 */
	protected function create_default_terms(): void {
		// Do nothing by default.
	}

	/**
	 * Get the admin filters array.
	 *
	 * @return array
	 */
	protected function get_admin_filters(): array {
		return [];
	}

	/**
	 * Get the admin columns array.
	 *
	 * @return array
	 */
	protected function get_admin_cols(): array {
		return [];
	}
}
