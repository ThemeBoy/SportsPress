<?php
/*
Plugin Name: SportsPress Importers
Plugin URI: http://themeboy.com/
Description: Add importers to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Importers' ) ) :

/**
 * Main SportsPress Importers Class
 *
 * @class SportsPress_Importers
 * @version	1.8.3
 */
class SportsPress_Importers {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'init', array( $this, 'includes' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_IMPORTERS_VERSION' ) )
			define( 'SP_IMPORTERS_VERSION', '1.8.3' );

		if ( !defined( 'SP_IMPORTERS_URL' ) )
			define( 'SP_IMPORTERS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_IMPORTERS_DIR' ) )
			define( 'SP_IMPORTERS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include importers.
	*/
	public function includes() {
		if ( is_admin() && defined( 'WP_LOAD_IMPORTERS' ) ) {
			include( SP()->plugin_path() . '/includes/admin/class-sp-admin-importers.php' );
		}
	}
}

endif;

new SportsPress_Importers();
