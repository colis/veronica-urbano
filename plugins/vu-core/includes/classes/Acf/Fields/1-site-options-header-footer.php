<?php
/**
 * ACF fields for the Site Options - Header & Footer page.
 *
 * @package VUCore
 */

use VUCore\Acf\Acf;

$fields = [];

foreach ( glob( __DIR__ . '/site-options/header-footer/*.php' ) as $file ) {
	require_once $file;
}

$location = [
	[
		[
			'param'    => 'options_page',
			'operator' => '==',
			'value'    => self::SITE_OPTIONS_HEADER_FOOTER_NAME,
		],
	],
];

acf_add_local_field_group(
	[
		'key'                   => Acf::META_KEY . 'group_site_options_header_footer',
		'title'                 => __( 'Header & Footer Site Options', 'vu-core' ),
		'fields'                => $fields,
		'location'              => $location,
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => '',
	]
);
