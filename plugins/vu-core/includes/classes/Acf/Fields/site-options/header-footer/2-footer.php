<?php
/**
 * ACF fields for Site Options - Header & Footer - Footer tab.
 *
 * @package VUCore
 */

use VUCore\Acf\Acf;

$fields = [
	...( isset( $fields ) ? $fields : [] ),
	...[
		[
			'key'               => Acf::META_KEY . 'footer_tab',
			'label'             => __( 'Footer', 'vu-core' ),
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
			'key'               => Acf::META_KEY . 'footer_logo',
			'label'             => __( 'Logo', 'vu-core' ),
			'name'              => 'footer_logo',
			'aria-label'        => '',
			'type'              => 'image',
			'instructions'      => __( 'Add the company logo to output in the footer.', 'vu-core' ),
			'required'          => 1,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '33',
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
			'key'               => Acf::META_KEY . 'footer_message',
			'label'             => __( 'Message', 'vu-core' ),
			'name'              => 'footer_message',
			'aria-label'        => '',
			'type'              => 'textarea',
			'instructions'      => __( 'Add a message to the footer.', 'vu-core' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '33',
				'class' => '',
				'id'    => '',
			],
			'default_value'     => '',
			'maxlength'         => '',
			'rows'              => 4,
			'placeholder'       => '',
			'new_lines'         => '',
		],
		[
			'key'               => Acf::META_KEY . 'footer_link',
			'label'             => __( 'Call-to-Action Link', 'vu-core' ),
			'name'              => 'footer_link',
			'aria-label'        => '',
			'type'              => 'link',
			'instructions'      => __( 'Add a call-to-action link to the footer.', 'vu-core' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '33',
				'class' => '',
				'id'    => '',
			],
			'return_format'     => 'array',
		],
		[
			'key'               => Acf::META_KEY . 'footer_copyright_text',
			'label'             => __( 'Copyright Text', 'vu-core' ),
			'name'              => 'footer_copyright_text',
			'aria-label'        => '',
			'type'              => 'text',
			'instructions'      => __( 'Copyright text that appears in the footer - {{year}} is replaced with the current year.', 'vu-core' ),
			'required'          => 0,
			'conditional_logic' => 0,
			'wrapper'           => [
				'width' => '',
				'class' => '',
				'id'    => '',
			],
			'default_value'     => '',
			'maxlength'         => '',
			'placeholder'       => '',
			'prepend'           => '',
			'append'            => '',
		],
	],
];
