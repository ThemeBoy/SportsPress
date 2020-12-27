<?php
/**
 * Plugin Name: SportsPress Pro
 * Plugin URI: http://tboy.co/pro
 * Description: Advanced club & league management from ThemeBoy.
 * Version: 2.7.6
 * Author: ThemeBoy
 * Author URI: http://themeboy.com
 * Requires at least: 3.8
 * Tested up to: 5.6
 *
 * Text Domain: sportspress
 * Domain Path: /languages/
 *
 * @package SportsPress_Pro
 * @category Pro
 * @author ThemeBoy
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SportsPress_Pro' ) ) :

/**
 * Main SportsPress Pro Class
 *
 * @class SportsPress_Pro
 * @version	2.7.6
 */
final class SportsPress_Pro {

	/**
	 * @var string
	 */
	public $version = '2.7.6';

	/**
	 * SportsPress Pro Constructor.
	 * @access public
	 * @return SportsPress_Pro
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// Install
		if ( class_exists( 'SP_Install' ) ):
			$install = new SP_Install();
			register_activation_hook( SP_PRO_PLUGIN_FILE, array( $install, 'install' ) );
		endif;

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		add_filter( 'sportspress_setup_wizard_next_steps', array( $this, 'next_steps' ) );
		add_action( 'admin_init', array( $this, 'deactivate_core' ) );
		add_action( 'before_sportspress_init', array( $this, 'load_module_translations' ), 0 );
		add_action( 'get_the_generator_html', array( $this, 'generator_tag' ), 10, 2 );
		add_action( 'get_the_generator_xhtml', array( $this, 'generator_tag' ), 10, 2 );
		add_action( 'themeboy_required_plugins', array( $this, 'unrequire_core' ) );
		add_action( 'sportspress_before_welcome_features', array( $this, 'welcome_features' ) );

		// Loaded action
		do_action( 'sportspress_pro_loaded' );
	}

	/**
	 * Define SP Constants.
	 */
	private function define_constants() {
		define( 'SP_PRO_PLUGIN_FILE', __FILE__ );
		define( 'SP_PRO_VERSION', $this->version );

		if ( ! defined( 'SP_PRO_TEMPLATE_PATH' ) ) {
			define( 'SP_PRO_TEMPLATE_PATH', $this->template_path() );
		}

		if ( ! defined( 'SP_PRO_DELIMITER' ) ) {
			define( 'SP_PRO_DELIMITER', '|' );
		}
	}

	/**
	 * Include SportsPress core and modules.
	 */
	private function includes() {
		$dir = scandir( $this->plugin_path() . '/includes' );
		if ( $dir ) {
			foreach ( $dir as $module ) {
				$path = $this->plugin_path() . '/includes/' . $module;
				if ( $path && substr( $module, 0, 1 ) !== '.' ) {
					$file = '/' . $module . '.php';
					if ( is_readable( $path . $file ) ) {
						include_once( $path . $file );
					}
				}
			}
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @param mixed $links
	 * @return array
	 */
	public function action_links( $links ) {
		return array_merge( array(
			'<a href="' . admin_url( 'admin.php?page=sportspress' ) . '">' . __( 'Settings', 'sportspress' ) . '</a>',
			'<a href="' . apply_filters( 'sportspress_docs_url', 'http://tboy.co/docs' ) . '">' . __( 'Docs', 'sportspress' ) . '</a>',
		), $links );
	}

	/**
	 * Final step in setup wizard.
	 */
	public function next_steps( $steps ) {
		$steps['last'] = array(
      'label' => __( 'SportsPress Themes', 'sportspress' ),
      'content' => __( 'Install an official SportsPress theme for 100% guaranteed compatibility with SportsPress Pro features.', 'sportspress' ) . ' <a href="http://tboy.co/themes" target="_blank">' . __( 'Learn more', 'sportspress' ) . '</a>',
    );

    return $steps;
	}

	/**
	 * Deactivate core.
	 */
	public function deactivate_core() {
		if ( is_plugin_active( 'sportspress/sportspress.php' ) ) {
			deactivate_plugins( 'sportspress/sportspress.php' );
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_module_translations() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sportspress' );

		// Global + Frontend Locale
		load_plugin_textdomain( 'sportspress-pro', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
	}

	/**
	 * Output generator tag to aid debugging.
	 */
	function generator_tag( $gen, $type ) {
		switch ( $type ) {
			case 'html':
				$gen .= "\n" . '<meta name="generator" content="SportsPress Pro ' . esc_attr( SP_PRO_VERSION ) . '">';
				break;
			case 'xhtml':
				$gen .= "\n" . '<meta name="generator" content="SportsPress Pro ' . esc_attr( SP_PRO_VERSION ) . '" />';
				break;
		}
		return $gen;
	}

	/**
	 * Unrequire SportsPress core.
	 */
	function unrequire_core( $plugins = array() ) {
		foreach ( $plugins as $index => $plugin ):
			if ( sp_array_value( $plugin, 'slug' ) == 'sportspress' ) unset( $plugins[ $index ] );
		endforeach;
		return $plugins;
	}

	/**
	 * Welcome page features.
	 */
	function welcome_features() {
		?>
		<div class="feature-section one-col">
			<div class="col">
				<h2>Pro Updates 🏆</h2>
			</div>
		</div>

		<div class="feature-section three-col">
			<div class="col">
				<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-results-matrix.png" alt="Results Matrix">
				<h3>Results Matrix</h3>
				<p>Display matches between home and away team in a grid. Create or select an existing calendar and select the <strong>Matrix</strong> layout to convert the calendar to an interactive results matrix!</p>
			</div>
			<div class="col">
				<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-midseason-transfers.png" alt="Midseason Transfers">
				<h3>Midseason Transfers</h3>
				<p>Keep track of players that switched teams during a season by adding one or more extra rows to their statistics table. Display the team and partial statistics before and after the transfer.<p>
			</div>
			<div class="col">
				<img src="<?php echo plugin_dir_url( SP_PLUGIN_FILE ); ?>assets/images/welcome/screenshot-vertical-timelines.png" alt="Vertical Timelines">
				<h3>Vertical Timelines</h3>
				<p>Display a match commentary style play-by-play timeline within events. To enable the new layout, visit <a href="<?php echo add_query_arg( array( 'page' => 'sportspress', 'tab' => 'events' ), admin_url( 'admin.php' ) ); ?>">Event Settings</a>, scroll down to the <strong>Timelines</strong> section and select the <strong>Vertical</strong> layout.<p>
			</div>
		</div>

		<hr>
		<?php
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
		return apply_filters( 'SP_PRO_TEMPLATE_PATH', 'sportspress-pro/' );
	}
}

endif;

new SportsPress_Pro();
