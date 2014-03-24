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

		// Libraries
		require_once dirname( __FILE__ ) . '/lib/eos/eos.class.php' ;

		// Globals
		require_once dirname( __FILE__ ) . '/admin/includes/globals.php';

		// Functions
		require_once dirname( __FILE__ ) . '/functions.php';
		require_once dirname( __FILE__ ) . '/includes/sp-deprecated-functions.php';

		// Templates
		require_once dirname( __FILE__ ) . '/admin/templates/countdown.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-details.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-performance.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-results.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-staff.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-venue.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-calendar.php';
		require_once dirname( __FILE__ ) . '/admin/templates/event-list.php';
		require_once dirname( __FILE__ ) . '/admin/templates/league-table.php';
		require_once dirname( __FILE__ ) . '/admin/templates/player-league-performance.php';
		require_once dirname( __FILE__ ) . '/admin/templates/player-list.php';
		//require_once dirname( __FILE__ ) . '/admin/templates/player-roster.php';
		require_once dirname( __FILE__ ) . '/admin/templates/player-gallery.php';
		require_once dirname( __FILE__ ) . '/admin/templates/player-metrics.php';
		require_once dirname( __FILE__ ) . '/admin/templates/player-performance.php';
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
		require_once dirname( __FILE__ ) . '/admin/post-types/performance.php';
		//require_once dirname( __FILE__ ) . '/admin/post-types/statistic.php';
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
		require_once dirname( __FILE__ ) . '/admin/widgets/countdown.php';
		require_once dirname( __FILE__ ) . '/admin/widgets/event-calendar.php';
		require_once dirname( __FILE__ ) . '/admin/widgets/event-list.php';
		require_once dirname( __FILE__ ) . '/admin/widgets/league-table.php';
		require_once dirname( __FILE__ ) . '/admin/widgets/player-list.php';
		require_once dirname( __FILE__ ) . '/admin/widgets/player-gallery.php';
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
		load_plugin_textdomain( 'sportspress', false, plugin_basename( dirname( __FILE__ ) ) . "/languages" );
	}

	/**
	 * Ensure theme and server variable compatibility and setup image sizes..
	 */
	public function setup_environment() {
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
