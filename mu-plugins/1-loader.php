<?php

// Composer autoloader -- must be first.
if ( ! is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	exit( 'Cannot find Composer autoload.' );
}
require_once __DIR__ . '/vendor/autoload.php';

// Load vu-mu-plugins here!
$vu_mu_plugins = [];

foreach ( $vu_mu_plugins as $plugin_path ) {

	if ( empty( $plugin_path ) ) {
		continue;
	}

	if ( ! is_readable( __DIR__ . $plugin_path ) ) {
		exit( esc_html( "Cannot find a vu-mu-plugin with slug {$plugin_path}" ) );
	}

	require_once __DIR__ . $plugin_path;
}
