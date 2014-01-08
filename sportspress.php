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
define( 'SPORTSPRESS_PLUGIN_FILE', __FILE__ );

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
require_once dirname( __FILE__ ) . '/admin/hooks/plugins-loaded.php';
require_once dirname( __FILE__ ) . '/admin/hooks/after-setup-theme.php';
require_once dirname( __FILE__ ) . '/admin/hooks/wp-enqueue-scripts.php';

// Admin request actions
require_once dirname( __FILE__ ) . '/admin/hooks/admin-menu.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-init.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-enqueue-scripts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-head.php';

// Administrative actions
require_once dirname( __FILE__ ) . '/admin/hooks/manage-posts-custom-column.php';
require_once dirname( __FILE__ ) . '/admin/hooks/post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/hooks/restrict-manage-posts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/save-post.php';

// Filters
require_once dirname( __FILE__ ) . '/admin/hooks/admin-post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/hooks/gettext.php';
require_once dirname( __FILE__ ) . '/admin/hooks/pre-get-posts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/sanitize-title.php';
require_once dirname( __FILE__ ) . '/admin/hooks/the-content.php';
require_once dirname( __FILE__ ) . '/admin/hooks/wp-insert-post-data.php';
require_once dirname( __FILE__ ) . '/admin/hooks/plugin-action-links.php';

// Register activation hook
require_once dirname( __FILE__ ) . '/admin/hooks/register-activation-hook.php';

