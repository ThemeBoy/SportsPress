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

// Globals
include dirname( __FILE__ ) . '/globals.php' ;

// Helpers
require_once dirname( __FILE__ ) . '/helpers.php';

// Settings
include dirname( __FILE__ ) . '/settings.php' ;

// Custom Post Types
require_once dirname( __FILE__ ) . '/team.php';
require_once dirname( __FILE__ ) . '/event.php';
require_once dirname( __FILE__ ) . '/player.php';
require_once dirname( __FILE__ ) . '/staff.php';
require_once dirname( __FILE__ ) . '/table.php';
require_once dirname( __FILE__ ) . '/calendar.php';
require_once dirname( __FILE__ ) . '/tournament.php';

// Taxonomies
require_once dirname( __FILE__ ) . '/league.php';
require_once dirname( __FILE__ ) . '/venue.php';
require_once dirname( __FILE__ ) . '/position.php';
require_once dirname( __FILE__ ) . '/sponsor.php';

// Styles
include_once dirname( __FILE__ ) . '/styles.php' ;

// Scripts
include_once dirname( __FILE__ ) . '/scripts.php' ;

// Hooks, Actions, and Filters
require_once dirname( __FILE__ ) . '/hooks.php';
require_once dirname( __FILE__ ) . '/actions.php';
require_once dirname( __FILE__ ) . '/filters.php';
?>