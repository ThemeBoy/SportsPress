<?php
/*
Plugin Name: SportsPress Widgets
Plugin URI: http://themeboy.com/
Description: Add widgets to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Widgets' ) ) :

/**
 * Main SportsPress Widgets Class
 *
 * @class SportsPress_Widgets
 * @version	1.8.3
 */
class SportsPress_Widgets {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'widgets_init', array( $this, 'includes' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_WIDGETS_VERSION' ) )
			define( 'SP_WIDGETS_VERSION', '1.8.3' );

		if ( !defined( 'SP_WIDGETS_URL' ) )
			define( 'SP_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_WIDGETS_DIR' ) )
			define( 'SP_WIDGETS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include widgets.
	 */
	public function includes() {
		include_once( SP()->plugin_path()  . '/includes/widgets/class-sp-widget-staff.php' );

		do_action( 'sportspress_widgets' );
	}
}

endif;

new SportsPress_Widgets();
