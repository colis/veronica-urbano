<?php
/**
 * VU definitions and hooks
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package VUTheme
 * @since 1.0.0
 */

use VUCore\PostType\Project;
use VUTheme\Core;
use VUTheme\Enqueue;
use VUTheme\TemplateTags;

if ( ! function_exists( 'cc' ) ) {
	return;
}

/**
 * Define constants.
 */
define( 'VU_THEME_VERSION', '1.25.0' );
define( 'VU_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'VU_THEME_URL', trailingslashit( get_template_directory_uri() ) );
define( 'VU_THEME_DIST_URL', trailingslashit( get_template_directory_uri() . '/dist' ) );

/**
 * Hooks.
 */
add_action( 'after_setup_theme', [ cc()->get( Core::class ), 'theme_setup' ] );

add_action( 'wp_enqueue_scripts', [ cc()->get( Enqueue::class ), 'vu_theme_frontend_styles' ] );
add_action( 'wp_footer', [ cc()->get( Enqueue::class ), 'vu_theme_enqueue_frontend_styles_late' ], 5 );
add_action( 'wp_enqueue_scripts', [ cc()->get( Enqueue::class ), 'vu_theme_frontend_scripts' ] );
add_action( 'admin_enqueue_scripts', [ cc()->get( Enqueue::class ), 'vu_theme_backend_scripts' ] );
add_action( 'after_setup_theme', [ cc()->get( Enqueue::class ), 'vu_theme_editor_styles' ] );
add_action( 'wp_head', [ cc()->get( Enqueue::class ), 'vu_theme_importmap_polyfill' ], 1 );
add_action( 'wp_head', [ cc()->get( Enqueue::class ), 'vu_theme_preload_requests' ], 1 );
add_action( 'wp_head', [ cc()->get( Enqueue::class ), 'vu_theme_favicons' ], 1 );
add_action( 'wp_head', [ cc()->get( Enqueue::class ), 'vu_iubenda_script' ], 1 );

add_action( 'vu_theme_' . Project::NAME . '_single_footer', [ cc()->get( TemplateTags::class ), 'vu_theme_project_nav' ] );
