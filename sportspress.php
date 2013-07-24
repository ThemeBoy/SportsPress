<?php
/**
 * @package SportsPress
 */
/*
Plugin Name: SportsPress
Plugin URI: http://sportspress.com/sportspress
Description: Currently in development.
Version: 0.1
Author: ThemeBoy
Author URI: http://sportspress.com
License: GPL2
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SPORTSPRESS_VERSION', '0.1' );
define( 'SPORTSPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Helpers
require_once dirname( __FILE__ ) . '/helpers.php';

// Defaults
include dirname( __FILE__ ) . '/defaults.php' ;

// Settings
include dirname( __FILE__ ) . '/settings.php' ;

// Custom post types
require_once dirname( __FILE__ ) . '/team.php';
require_once dirname( __FILE__ ) . '/event.php';
require_once dirname( __FILE__ ) . '/player.php';
require_once dirname( __FILE__ ) . '/staff.php';
require_once dirname( __FILE__ ) . '/table.php';
require_once dirname( __FILE__ ) . '/calendar.php';
require_once dirname( __FILE__ ) . '/tournament.php';

// Taxonomies
require_once dirname( __FILE__ ) . '/league.php';
require_once dirname( __FILE__ ) . '/season.php';
require_once dirname( __FILE__ ) . '/venue.php';
require_once dirname( __FILE__ ) . '/position.php';
require_once dirname( __FILE__ ) . '/sponsor.php';

// Stylesheets
include dirname( __FILE__ ) . '/styles.php' ;

// Flush rewrite rules on activation to make sure permalinks work properly
function sp_rewrite_flush() {
    sp_team_cpt_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sp_rewrite_flush' );

function sp_init() {
	add_theme_support( 'post-thumbnails' );
    load_plugin_textdomain ( 'sportspress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	add_image_size( 'sp_icon',  32, 32, false );
}
add_action( 'plugins_loaded', 'sp_init' );
?>