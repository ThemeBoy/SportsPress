<?php
/**
 * @package SportsPress
 */
/*
Plugin Name: SportsPress
Plugin URI: http://themeboy.com/sportspress
Description: Manage your club and its players, staff, events, league tables, and player lists.
Version: 0.1
Author: ThemeBoy
Author URI: http://themeboy.com
License: GPLv3
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SPORTSPRESS_VERSION', '0.1' );
define( 'SPORTSPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Libraries
include dirname( __FILE__ ) . '/lib/classes/eos.class.php' ;

// Globals
include dirname( __FILE__ ) . '/sportspress-globals.php' ;

// Helpers
require_once dirname( __FILE__ ) . '/sportspress-helpers.php';

// Settings
include dirname( __FILE__ ) . '/sportspress-settings.php' ;

// Custom Post Types
require_once dirname( __FILE__ ) . '/admin/post-types/event.php';
require_once dirname( __FILE__ ) . '/admin/post-types/result.php';
require_once dirname( __FILE__ ) . '/admin/post-types/outcome.php';
require_once dirname( __FILE__ ) . '/admin/post-types/team.php';
require_once dirname( __FILE__ ) . '/admin/post-types/table.php';
require_once dirname( __FILE__ ) . '/admin/post-types/stat.php';
require_once dirname( __FILE__ ) . '/admin/post-types/player.php';
require_once dirname( __FILE__ ) . '/admin/post-types/list.php';
require_once dirname( __FILE__ ) . '/admin/post-types/metric.php';
require_once dirname( __FILE__ ) . '/admin/post-types/staff.php';

// Terms
require_once dirname( __FILE__ ) . '/admin/terms/div.php';
require_once dirname( __FILE__ ) . '/admin/terms/pos.php';
require_once dirname( __FILE__ ) . '/admin/terms/sport.php';

// Shortcodes
// require_once dirname( __FILE__ ) . '/classes/shortcodes/table.php';

// Hooks, Actions, and Filters
require_once dirname( __FILE__ ) . '/sportspress-hooks.php';
require_once dirname( __FILE__ ) . '/sportspress-actions.php';
require_once dirname( __FILE__ ) . '/sportspress-filters.php';

// Admin Styles
function sp_admin_styles() {
	wp_register_style( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL . 'assets/css/admin.css', array(), '1.0' );
	wp_enqueue_style( 'sportspress-admin');
}
add_action( 'admin_init', 'sp_admin_styles' );

// Admin Scripts
function sp_admin_enqueue_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'sportspress-admin', SPORTSPRESS_PLUGIN_URL .'/assets/js/admin.js', array( 'jquery' ), time(), true );
}
add_action( 'admin_enqueue_scripts', 'sp_admin_enqueue_scripts' );
?>