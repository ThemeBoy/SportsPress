<?php
/**
 * Plugin Name: SportsPress Pro
 * Plugin URI: http://sportspresspro.com/
 * Description: Manage your club and its players, staff, events, league tables, and player lists.
 * Version: 1.2.4
 * Author: ThemeBoy
 * Author URI: http://themeboy.com
 * Requires at least: 3.8
 * Tested up to: 3.9.1
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
 * @version	1.2.4
 */
final class SportsPress_Pro {

	/**
	 * @var string
	 */
	public $version = '1.2.4';

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

		// Hooks
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );

		// Loaded action
		do_action( 'sportspress_pro_loaded' );
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
			'<a href="' . apply_filters( 'sportspress_docs_url', 'http://sportspresspro.com/docs/' ) . '">' . __( 'Docs', 'sportspress' ) . '</a>',
			'<a href="' . apply_filters( 'sportspress_support_url', 'http://sportspresspro.com/support/' ) . '">' . __( 'Support', 'sportspress' ) . '</a>',
		), $links );
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
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
		// Core
		include_once( $this->plugin_path() . '/core/sportspress.php' );

		// Modules
		$dir = scandir( $this->plugin_path() . '/modules' );
		if ( $dir ) {
			foreach ( $dir as $module ) {
				$path = $this->plugin_path() . '/modules/' . $module;
				if ( $path && substr( $module, 0, 1 ) !== '.' ) {
					$file = '/' . $module . '.php';
					if ( is_readable( $path . $file ) ) {
						include_once( $path . $file );
					}
				}
			}
		}
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
