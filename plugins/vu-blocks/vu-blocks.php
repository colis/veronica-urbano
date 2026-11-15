<?php
/**
 * Plugin Name: VU Blocks
 * Plugin URI:  https://github.com/colis/veronica-urbano
 * Description: Custom Gutenberg blocks for the veronicaurbano.it site.
 * Version:     1.0.0
 * Author:      Carmine Colicino
 * Author URI:  https://github.com/colis
 * Text Domain: vu-blocks
 *
 * @package VUBlocks
 */

namespace VUBlocks;

// Useful global constants.
define( 'VU_BLOCKS_VERSION', '1.25.0' );
define( 'VU_BLOCKS_URL', plugin_dir_url( __FILE__ ) );
define( 'VU_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'VU_BLOCKS_INC', VU_BLOCKS_PATH . 'includes/' );
define( 'VU_BLOCKS_CACHE_GROUP', 'vu_blocks_plugin' );
define( 'VU_BLOCKS_CACHE_EXPIRY', 300 );

// Initialise plugin.
( new Plugin() )->register();
