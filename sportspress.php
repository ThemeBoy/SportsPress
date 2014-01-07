<?php
/**
 * @package SportsPress
 */
/*
Plugin Name: SportsPress
Plugin URI: http://themeboy.com/sportspress
Description: Manage your club and its players, staff, events, league tables, and player lists.
Version: 0.1.3
Author: ThemeBoy
Author URI: http://themeboy.com/
License: GPLv3
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'SPORTSPRESS_VERSION', '0.1.3' );
define( 'SPORTSPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPORTSPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Libraries
include dirname( __FILE__ ) . '/lib/eos/eos.class.php' ;

// Internationalization
include dirname( __FILE__ ) . '/i18n/strings.php';

// Functions
require_once dirname( __FILE__ ) . '/functions.php';

// Settings
include dirname( __FILE__ ) . '/admin/settings/settings.php' ;

// Custom Post Types
require_once dirname( __FILE__ ) . '/admin/post-types/separator.php';
require_once dirname( __FILE__ ) . '/admin/post-types/event.php';
require_once dirname( __FILE__ ) . '/admin/post-types/result.php';
require_once dirname( __FILE__ ) . '/admin/post-types/outcome.php';
require_once dirname( __FILE__ ) . '/admin/post-types/column.php';
require_once dirname( __FILE__ ) . '/admin/post-types/statistic.php';
require_once dirname( __FILE__ ) . '/admin/post-types/team.php';
require_once dirname( __FILE__ ) . '/admin/post-types/table.php';
require_once dirname( __FILE__ ) . '/admin/post-types/player.php';
require_once dirname( __FILE__ ) . '/admin/post-types/list.php';
require_once dirname( __FILE__ ) . '/admin/post-types/staff.php';

// Terms
require_once dirname( __FILE__ ) . '/admin/terms/season.php';
require_once dirname( __FILE__ ) . '/admin/terms/position.php';

// Presets
include_once dirname( __FILE__ ) . '/admin/presets/soccer.php';
include_once dirname( __FILE__ ) . '/admin/presets/football.php';
include_once dirname( __FILE__ ) . '/admin/presets/footy.php';
include_once dirname( __FILE__ ) . '/admin/presets/baseball.php';
include_once dirname( __FILE__ ) . '/admin/presets/basketball.php';
include_once dirname( __FILE__ ) . '/admin/presets/gaming.php';
include_once dirname( __FILE__ ) . '/admin/presets/cricket.php';
include_once dirname( __FILE__ ) . '/admin/presets/golf.php';
include_once dirname( __FILE__ ) . '/admin/presets/handball.php';
include_once dirname( __FILE__ ) . '/admin/presets/hockey.php';
include_once dirname( __FILE__ ) . '/admin/presets/racing.php';
include_once dirname( __FILE__ ) . '/admin/presets/rugby.php';
include_once dirname( __FILE__ ) . '/admin/presets/swimming.php';
include_once dirname( __FILE__ ) . '/admin/presets/tennis.php';
include_once dirname( __FILE__ ) . '/admin/presets/volleyball.php';

// Install
include dirname( __FILE__ ) . '/install.php';

// Actions
require_once dirname( __FILE__ ) . '/admin/actions/plugins-loaded.php';
require_once dirname( __FILE__ ) . '/admin/actions/post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/actions/after-theme-setup.php';
require_once dirname( __FILE__ ) . '/admin/actions/admin-menu.php';
require_once dirname( __FILE__ ) . '/admin/actions/manage-posts-custom-column.php';
require_once dirname( __FILE__ ) . '/admin/actions/restrict-manage-posts.php';
require_once dirname( __FILE__ ) . '/admin/actions/save-post.php';

// Filters
require_once dirname( __FILE__ ) . '/admin/filters/gettext.php';
require_once dirname( __FILE__ ) . '/admin/filters/admin-post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/filters/the-content.php';
require_once dirname( __FILE__ ) . '/admin/filters/sanitize-title.php';
require_once dirname( __FILE__ ) . '/admin/filters/wp-insert-post-data.php';
require_once dirname( __FILE__ ) . '/admin/filters/pre-get-posts.php';

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
