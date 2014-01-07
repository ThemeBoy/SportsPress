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

// Strings
include dirname( __FILE__ ) . '/strings.php';

// Functions
require_once dirname( __FILE__ ) . '/admin-functions.php';
require_once dirname( __FILE__ ) . '/functions.php';

// Settings
include dirname( __FILE__ ) . '/admin/settings/settings.php' ;

// Custom post types
require_once dirname( __FILE__ ) . '/admin/post-types/separator.php';
require_once dirname( __FILE__ ) . '/admin/post-types/column.php';
require_once dirname( __FILE__ ) . '/admin/post-types/statistic.php';
require_once dirname( __FILE__ ) . '/admin/post-types/metric.php';
require_once dirname( __FILE__ ) . '/admin/post-types/result.php';
require_once dirname( __FILE__ ) . '/admin/post-types/outcome.php';
require_once dirname( __FILE__ ) . '/admin/post-types/event.php';
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

// Typical request actions
require_once dirname( __FILE__ ) . '/admin/actions/plugins-loaded.php';
require_once dirname( __FILE__ ) . '/admin/actions/after-setup-theme.php';
require_once dirname( __FILE__ ) . '/admin/actions/wp-enqueue-scripts.php';

// Admin request actions
require_once dirname( __FILE__ ) . '/admin/actions/admin-menu.php';
require_once dirname( __FILE__ ) . '/admin/actions/admin-init.php';
require_once dirname( __FILE__ ) . '/admin/actions/admin-enqueue-scripts.php';
require_once dirname( __FILE__ ) . '/admin/actions/admin-head.php';

// Administrative actions
require_once dirname( __FILE__ ) . '/admin/actions/manage-posts-custom-column.php';
require_once dirname( __FILE__ ) . '/admin/actions/post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/actions/restrict-manage-posts.php';
require_once dirname( __FILE__ ) . '/admin/actions/save-post.php';

// Filters
require_once dirname( __FILE__ ) . '/admin/filters/admin-post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/filters/gettext.php';
require_once dirname( __FILE__ ) . '/admin/filters/pre-get-posts.php';
require_once dirname( __FILE__ ) . '/admin/filters/sanitize-title.php';
require_once dirname( __FILE__ ) . '/admin/filters/the-content.php';
require_once dirname( __FILE__ ) . '/admin/filters/wp-insert-post-data.php';

// Flush rewrite rules on activation
function sportspress_rewrite_flush() {
    sportspress_event_post_init();
    sportspress_result_post_init();
    sportspress_outcome_post_init();
    sportspress_column_post_init();
    sportspress_statistic_post_init();
    sportspress_team_post_init();
    sportspress_table_post_init();
    sportspress_player_post_init();
    sportspress_list_post_init();
    sportspress_staff_post_init();
    sportspress_position_term_init();
    sportspress_season_term_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sportspress_rewrite_flush' );