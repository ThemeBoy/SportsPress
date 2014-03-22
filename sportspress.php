<?php
/**
 * @package SportsPress
 */
/*
Plugin Name: SportsPress
Plugin URI: http://themeboy.com/sportspress
Description: Manage your club and its players, staff, events, league tables, and player lists.
Version: 0.6.1
Author: ThemeBoy
Author URI: http://themeboy.com/
License: GPLv3
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ):
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
endif;

define( 'SPORTSPRESS_VERSION', '0.6.1' );
define( 'SPORTSPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SPORTSPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'SPORTSPRESS_PLUGIN_FILE', __FILE__ );

// Libraries
require_once dirname( __FILE__ ) . '/lib/eos/eos.class.php' ;

// Globals
require_once dirname( __FILE__ ) . '/admin/includes/globals.php';

// Functions
require_once dirname( __FILE__ ) . '/functions.php';

// Templates
require_once dirname( __FILE__ ) . '/admin/templates/countdown.php';
require_once dirname( __FILE__ ) . '/admin/templates/event-details.php';
require_once dirname( __FILE__ ) . '/admin/templates/event-players.php';
require_once dirname( __FILE__ ) . '/admin/templates/event-results.php';
require_once dirname( __FILE__ ) . '/admin/templates/event-staff.php';
require_once dirname( __FILE__ ) . '/admin/templates/event-venue.php';
require_once dirname( __FILE__ ) . '/admin/templates/events.php';
require_once dirname( __FILE__ ) . '/admin/templates/events-calendar.php';
require_once dirname( __FILE__ ) . '/admin/templates/events-list.php';
require_once dirname( __FILE__ ) . '/admin/templates/league-table.php';
require_once dirname( __FILE__ ) . '/admin/templates/player-league-statistics.php';
require_once dirname( __FILE__ ) . '/admin/templates/player-list.php';
//require_once dirname( __FILE__ ) . '/admin/templates/player-roster.php';
require_once dirname( __FILE__ ) . '/admin/templates/player-gallery.php';
require_once dirname( __FILE__ ) . '/admin/templates/player-metrics.php';
require_once dirname( __FILE__ ) . '/admin/templates/player-statistics.php';
require_once dirname( __FILE__ ) . '/admin/templates/team-columns.php';

// Options
require_once dirname( __FILE__ ) . '/admin/settings/settings.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-general.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-event.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-team.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-player.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-text.php';
require_once dirname( __FILE__ ) . '/admin/settings/options-permalink.php';

// Custom post types
require_once dirname( __FILE__ ) . '/admin/post-types/separator.php';
require_once dirname( __FILE__ ) . '/admin/post-types/column.php';
require_once dirname( __FILE__ ) . '/admin/post-types/statistic.php';
require_once dirname( __FILE__ ) . '/admin/post-types/metric.php';
require_once dirname( __FILE__ ) . '/admin/post-types/result.php';
require_once dirname( __FILE__ ) . '/admin/post-types/outcome.php';
require_once dirname( __FILE__ ) . '/admin/post-types/event.php';
require_once dirname( __FILE__ ) . '/admin/post-types/calendar.php';
require_once dirname( __FILE__ ) . '/admin/post-types/team.php';
require_once dirname( __FILE__ ) . '/admin/post-types/table.php';
require_once dirname( __FILE__ ) . '/admin/post-types/player.php';
require_once dirname( __FILE__ ) . '/admin/post-types/list.php';
require_once dirname( __FILE__ ) . '/admin/post-types/staff.php';
//require_once dirname( __FILE__ ) . '/admin/post-types/directory.php';

// Terms
require_once dirname( __FILE__ ) . '/admin/terms/league.php';
require_once dirname( __FILE__ ) . '/admin/terms/season.php';
require_once dirname( __FILE__ ) . '/admin/terms/venue.php';
require_once dirname( __FILE__ ) . '/admin/terms/position.php';

// Widgets
require_once dirname( __FILE__ ) . '/admin/widgets/countdown.php';
require_once dirname( __FILE__ ) . '/admin/widgets/events-calendar.php';
require_once dirname( __FILE__ ) . '/admin/widgets/events-list.php';
require_once dirname( __FILE__ ) . '/admin/widgets/league-table.php';
require_once dirname( __FILE__ ) . '/admin/widgets/player-list.php';
require_once dirname( __FILE__ ) . '/admin/widgets/player-gallery.php';

// Tools
require_once dirname( __FILE__ ) . '/admin/tools/importers.php';

// Typical request actions
require_once dirname( __FILE__ ) . '/admin/hooks/plugins-loaded.php';
require_once dirname( __FILE__ ) . '/admin/hooks/after-setup-theme.php';
require_once dirname( __FILE__ ) . '/admin/hooks/wp-enqueue-scripts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/loop-start.php';
require_once dirname( __FILE__ ) . '/admin/hooks/the-title.php';

// Admin request actions
require_once dirname( __FILE__ ) . '/admin/hooks/admin-init.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-menu.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-enqueue-scripts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-print-styles.php';
require_once dirname( __FILE__ ) . '/admin/hooks/admin-head.php';
require_once dirname( __FILE__ ) . '/admin/hooks/current-screen.php';

// Administrative actions
require_once dirname( __FILE__ ) . '/admin/hooks/manage-posts-columns.php';
require_once dirname( __FILE__ ) . '/admin/hooks/post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/hooks/restrict-manage-posts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/parse-query.php';;
require_once dirname( __FILE__ ) . '/admin/hooks/save-post.php';

// Filters
require_once dirname( __FILE__ ) . '/admin/hooks/admin-post-thumbnail-html.php';
require_once dirname( __FILE__ ) . '/admin/hooks/gettext.php';
require_once dirname( __FILE__ ) . '/admin/hooks/pre-get-posts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/the-posts.php';
require_once dirname( __FILE__ ) . '/admin/hooks/sanitize-title.php';
require_once dirname( __FILE__ ) . '/admin/hooks/the-content.php';
require_once dirname( __FILE__ ) . '/admin/hooks/widget-text.php';
require_once dirname( __FILE__ ) . '/admin/hooks/wp-insert-post-data.php';
require_once dirname( __FILE__ ) . '/admin/hooks/plugin-action-links.php';
require_once dirname( __FILE__ ) . '/admin/hooks/post-updated-messages.php';

// Register activation hook
require_once dirname( __FILE__ ) . '/admin/hooks/register-activation-hook.php';
