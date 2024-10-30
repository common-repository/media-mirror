<?php
/**
 * Plugin Name: MediaMirror
 * Plugin URI: https://www.mediamirror.net
 * Description: WordpressPlugin for Media Mirror.
 * Author: Media Mirror
 * Version: 1.0.6
 * Author URI: https://www.mediamirror.net
 * Text Domain: mediamirror
 * Domain Path: /languages
 * License: GPL
 * Copyright: Rasmus Kjellberg
 *
 * @package MediaMirror
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly!
}

define( 'MEDIAMIRROR__VERSION', '1.0.4' );
define( 'MEDIAMIRROR__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MEDIAMIRROR__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Load Titan Framework Checker?
require_once( MEDIAMIRROR__PLUGIN_DIR . 'titan-framework/titan-framework-embedder.php' );

add_action( 'init', 'mediamirror_load_textdomain' );

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function mediamirror_load_textdomain() {
	load_plugin_textdomain( 'mediamirror', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Load helpers
require_once( MEDIAMIRROR__PLUGIN_DIR . '/lib/jigsaw.php' );
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/functions.php' );

// Load options panel.
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/mediamirror.settings.php' );

// Load casino post type.
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/casino.posttype.php' );
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/casino.meta.php' );

// Load sync algo.
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/sync.action.php' );

// Load casino table shortcode
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/casino-table.customizer.php' );
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/casino-table.shortcode.php' );

// Load front-end scripts and styles
require_once( MEDIAMIRROR__PLUGIN_DIR . '/src/front-end.php' );

// Load Visual Composer components
require_once( MEDIAMIRROR__PLUGIN_DIR . '/vc-components/vc_extend.php' );
require_once( MEDIAMIRROR__PLUGIN_DIR . '/vc-components/casino-table/casino-table.php' );
require_once( MEDIAMIRROR__PLUGIN_DIR . '/vc-components/casino-box/casino-box.php' );
