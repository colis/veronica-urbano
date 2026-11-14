<?php
/**
 * Project CPT.
 *
 * @package VUCore
 */

namespace VUCore\PostType;

/**
 * Project Class.
 *
 * @package VUCore\PostType
 */
class Project extends BasePostTypeAbstract {

	public const SLUG = 'project';
	public const NAME = 'project';
	public const ICON = 'dashicons-art';

	/**
	 * Set the arguments.
	 *
	 * @return array Array of arguments.
	 */
	protected function get_arguments(): array {
		return [
			'rewrite' => [
				'slug'       => self::SLUG,
				'with_front' => false,
			],
		];
	}

	/**
	 * Get the admin columns array.
	 *
	 * @return array
	 */
	protected function get_admin_cols(): array {

		$columns = [];

		$columns['title'] = [
			'title' => __( 'Title', 'vu-core' ),
		];

		$columns['categories'] = [
			'title'    => __( 'Categories', 'vu-core' ),

			'taxonomy' => 'categories',
		];

		$columns['tags'] = [
			'title'    => __( 'Tags', 'vu-core' ),
			'taxonomy' => 'tags',
		];

		$columns['author'] = [
			'title'  => __( 'Author', 'vu-core' ),
			'author' => 'author',
		];

		$columns['date'] = [
			'title' => __( 'Date', 'vu-core' ),
			'date'  => 'date',
		];

		return $columns;
	}
}
