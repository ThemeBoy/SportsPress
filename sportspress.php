<?php
/**
 * Plugin Name: SportsPress
 * Plugin URI: http://wordpress.org/plugins/sportspress
 * Description: Manage your club and its players, staff, events, league tables, and player lists.
 * Version: 0.7
 * Author: ThemeBoy
 * Author URI: http://themeboy.com
 * Requires at least: 3.8
 * Tested up to: 3.8
 *
 * Text Domain: sportspress
 * Domain Path: /languages/
 *
 * @package SportsPress
 * @category Core
 * @author ThemeBoy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SportsPress' ) ) :

/**
 * Main SportsPress Class
 *
 * @class SportsPress
 * @version	0.7
 */
final class SportsPress {

	/**
	 * @var string
	 */
	public $version = '0.7';

	/**
	 * @var SporsPress The single instance of the class
	 * @since 0.7
	 */
	protected static $_instance = null;

	/**
	 * Main SportsPress Instance
	 *
	 * Ensures only one instance of SportsPress is loaded or can be loaded.
	 *
	 * @since 0.7
	 * @static
	 * @see SP()
	 * @return SportsPress - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 0.7
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'sportspress' ), '0.7' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 0.7
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'sportspress' ), '0.7' );
	}

	/**
	 * SportsPress Constructor.
	 * @access public
	 * @return SportsPress
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Hooks
		add_action( 'widgets_init', array( $this, 'include_widgets' ) );
		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'after_setup_theme', array( $this, 'setup_environment' ) );

		// Loaded action
		do_action( 'sportspress_loaded' );
	}

	/**
	 * Define SP Constants
	 */
	private function define_constants() {
		define( 'SP_PLUGIN_FILE', __FILE__ );
		define( 'SP_VERSION', $this->version );

		if ( ! defined( 'SP_TEMPLATE_PATH' ) ) {
			define( 'SP_TEMPLATE_PATH', $this->template_path() );
		}

		if ( ! defined( 'SP_DELIMITER' ) ) {
			define( 'SP_DELIMITER', '|' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Globals
		include_once( 'admin/includes/globals.php' );

		// Functions
		include_once( 'includes/sp-core-functions.php' );
		include_once( 'includes/sp-deprecated-functions.php' );

		// Templates
		include_once( 'includes/templates/countdown.php' );
		include_once( 'includes/templates/event-details.php' );
		include_once( 'includes/templates/event-performance.php' );
		include_once( 'includes/templates/event-results.php' );
		include_once( 'includes/templates/event-staff.php' );
		include_once( 'includes/templates/event-venue.php' );
		include_once( 'includes/templates/event-calendar.php' );
		include_once( 'includes/templates/event-list.php' );
		include_once( 'includes/templates/league-table.php' );
		include_once( 'includes/templates/player-league-performance.php' );
		include_once( 'includes/templates/player-list.php' );
		//include_once( 'includes/templates/player-roster.php' );
		include_once( 'includes/templates/player-gallery.php' );
		include_once( 'includes/templates/player-metrics.php' );
		include_once( 'includes/templates/player-performance.php' );
		include_once( 'includes/templates/team-columns.php' );

		// Options
		include_once( 'admin/settings/settings.php' );
		include_once( 'admin/settings/options-general.php' );
		include_once( 'admin/settings/options-event.php' );
		include_once( 'admin/settings/options-team.php' );
		include_once( 'admin/settings/options-player.php' );
		include_once( 'admin/settings/options-text.php' );
		include_once( 'admin/settings/options-permalink.php' );

		// Custom post types
		include_once( 'admin/post-types/separator.php' );
		include_once( 'admin/post-types/column.php' );
		include_once( 'admin/post-types/performance.php' );
		//include_once( 'admin/post-types/statistic.php' );
		include_once( 'admin/post-types/metric.php' );
		include_once( 'admin/post-types/result.php' );
		include_once( 'admin/post-types/outcome.php' );
		include_once( 'admin/post-types/event.php' );
		include_once( 'admin/post-types/calendar.php' );
		include_once( 'admin/post-types/team.php' );
		include_once( 'admin/post-types/table.php' );
		include_once( 'admin/post-types/player.php' );
		include_once( 'admin/post-types/list.php' );
		include_once( 'admin/post-types/staff.php' );
		//include_once( 'admin/post-types/directory.php' );

		// Terms
		include_once( 'admin/terms/league.php' );
		include_once( 'admin/terms/season.php' );
		include_once( 'admin/terms/venue.php' );
		include_once( 'admin/terms/position.php' );

		// Tools
		include_once( 'admin/tools/importers.php' );

		// Typical request actions
		include_once( 'admin/hooks/plugins-loaded.php' );
		include_once( 'admin/hooks/wp-enqueue-scripts.php' );
		include_once( 'admin/hooks/loop-start.php' );
		include_once( 'admin/hooks/the-title.php' );

		// Admin request actions
		include_once( 'admin/hooks/admin-init.php' );
		include_once( 'admin/hooks/admin-menu.php' );
		include_once( 'admin/hooks/admin-enqueue-scripts.php' );
		include_once( 'admin/hooks/admin-print-styles.php' );
		include_once( 'admin/hooks/admin-head.php' );
		include_once( 'admin/hooks/current-screen.php' );

		// Administrative actions
		include_once( 'admin/hooks/manage-posts-columns.php' );
		include_once( 'admin/hooks/post-thumbnail-html.php' );
		include_once( 'admin/hooks/restrict-manage-posts.php' );
		include_once( 'admin/hooks/parse-query.php' );
		include_once( 'admin/hooks/save-post.php' );

		// Filters
		include_once( 'admin/hooks/admin-post-thumbnail-html.php' );
		include_once( 'admin/hooks/gettext.php' );
		include_once( 'admin/hooks/pre-get-posts.php' );
		include_once( 'admin/hooks/the-posts.php' );
		include_once( 'admin/hooks/sanitize-title.php' );
		include_once( 'admin/hooks/the-content.php' );
		include_once( 'admin/hooks/widget-text.php' );
		include_once( 'admin/hooks/wp-insert-post-data.php' );
		include_once( 'admin/hooks/plugin-action-links.php' );
		include_once( 'admin/hooks/post-updated-messages.php' );

		// Register activation hook
		include_once( 'admin/hooks/register-activation-hook.php' );
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
	}

	/**
	 * Function used to Init SportsPress Template Functions - This makes them pluggable by plugins and themes.
	 */
	public function include_template_functions() {
	}

	/**
	 * Include core widgets
	 */
	public function include_widgets() {
		include_once( 'includes/widgets/class-sp-widget-countdown.php' );
		include_once( 'includes/widgets/class-sp-widget-event-calendar.php' );
		include_once( 'includes/widgets/class-sp-widget-event-list.php' );
		include_once( 'includes/widgets/class-sp-widget-league-table.php' );
		include_once( 'includes/widgets/class-sp-widget-player-list.php' );
		include_once( 'includes/widgets/class-sp-widget-player-gallery.php' );
	}

	/**
	 * Init SportsPress when WordPress Initialises.
	 */
	public function init() {
		// Before init action
		do_action( 'before_sportspress_init' );

		// Set up localisation
		$this->load_plugin_textdomain();

		// Init action
		do_action( 'sportspress_init' );
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );
		
		// Global + Frontend Locale
		load_textdomain( 'sportspress', WP_LANG_DIR . "/sportspress/sportspress-$locale.mo" );
		load_plugin_textdomain( 'sportspress', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/**
	 * Ensure theme and server variable compatibility and setup image sizes..
	 */
	public function setup_environment() {
		add_theme_support( 'post-thumbnails' );
	}

	/** Helper functions ******************************************************/

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Get the template path.
	 *
	 * @return string
	 */
	public function template_path() {
		return apply_filters( 'SP_TEMPLATE_PATH', 'sportspress/' );
	}
}

endif;

/**
 * Returns the main instance of SP to prevent the need to use globals.
 *
 * @since  0.7
 * @return SportsPress
 */
function SP() {
	return SportsPress::instance();
}

SP();
