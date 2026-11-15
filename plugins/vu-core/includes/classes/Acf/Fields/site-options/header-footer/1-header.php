<?php
/**
 * ACF fields for Site Options - Header & Footer - Header tab.
 *
 * @package VUCore
 */

use VUCore\Acf\Acf;

// Build the fields array for the header tab.
$fields = [
	...( isset( $fields ) ? $fields : [] ),
	...[
		[
			'key'               => Acf::META_KEY . 'header_tab',
			'label'             => __( 'Header', 'vu-core' ),
			'name'              => '',
			'aria-label'        => '',
			'type'              => 'tab',
			'instructions'      => '',
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '',
				'class' => '',
				'id'    => '',
			],
			'placement'         => 'top',
			'endpoint'          => 0,
		],
		[
			'key'               => Acf::META_KEY . 'header_logo',
			'label'             => __( 'Logo', 'vu-core' ),
			'name'              => 'header_logo',
			'aria-label'        => '',
			'type'              => 'image',
			'instructions'      => __( 'Add the company logo to output in the header.', 'vu-core' ),
			'required'          => 1,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'return_format'     => 'id',
			'library'           => 'all',
			'min_width'         => '',
			'min_height'        => '',
			'min_size'          => '',
			'max_width'         => '',
			'max_height'        => '',
			'max_size'          => '',
			'mime_types'        => '',
			'preview_size'      => 'medium',
		],
		[
			'key'               => Acf::META_KEY . 'header_primary_button',
			'label'             => __( 'Call-to-Action Button', 'vu-core' ),
			'name'              => 'header_primary_button',
			'aria-label'        => '',
			'type'              => 'link',
			'instructions'      => __( 'Add a primary call-to-action button to the header.', 'vu-core' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '50',
				'class' => '',
				'id'    => '',
			],
			'return_format'     => 'array',
		],
	],
];
