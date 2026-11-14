<?php
/**
 * ACF fields for the Menu Item post type.
 *
 * @package VUCore
 */

use VUCore\Acf\Acf;
use VUCore\PostType\Project;

$fields = [
	[
		'key'               => 'archive_thumbnail',
		'label'             => 'Archive thumbnail',
		'name'              => 'archive_thumbnail',
		'aria-label'        => '',
		'type'              => 'image',
		'instructions'      => '',
		'required'          => 0,
		'conditional_logic' => 0,
		'wrapper'           => [
			'width' => '',
			'class' => '',
			'id'    => '',
		],
		'return_format'     => 'id',
		'preview_size'      => 'medium',
		'library'           => 'all',
		'min_width'         => '',
		'min_height'        => '',
		'min_size'          => '',
		'max_width'         => '',
		'max_height'        => '',
		'max_size'          => '',
		'mime_types'        => '',
	],
];

$location = [
	[
		[
			'param'    => 'post_type',
			'operator' => '==',
			'value'    => Project::NAME,
		],
	],
];

acf_add_local_field_group(
	[
		'key'                   => Acf::META_KEY . 'archive',
		'title'                 => __( 'Archive', 'vu-core' ),
		'fields'                => $fields,
		'location'              => $location,
		'menu_order'            => 0,
		'position'              => 'side',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
		'show_in_rest'          => false,
		'display_title'         => '',
		'allow_ai_access'       => false,
		'ai_description'        => '',
	]
);
