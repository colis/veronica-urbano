<?php
/**
 * Plugin Name: VU Core
 * Plugin URI:  https://github.com/colis/veronica-urbano
 * Description: Core functionality for the veronicaurbano.it site.
 * Version:     1.0.0
 * Author:      Carmine Colicino
 * Author URI:  https://github.com/colis
 * Text Domain: vu-core
 * Domain Path: /languages
 *
 * @package VUCore
 */

namespace VUCore;

// Useful global constants.
define( 'VU_CORE_VERSION', '1.0.0' );
define( 'VU_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'VU_CORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'VU_CORE_INC', VU_CORE_PATH . 'includes/' );
define( 'VU_CORE_CACHE_GROUP', 'vu_core_plugin' );

// Initialise plugin.
( new Plugin() )->register();
