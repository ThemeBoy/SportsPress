<?php
/*
Plugin Name: SportsPress Default Nationality
Plugin URI: http://themeboy.com/
Description: Add default nationality option to SportsPress Settings.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.18
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Default_Nationality' ) ) :

/**
 * Main SportsPress Default Nationality Class
 *
 * @class SportsPress_Default_Nationality
 * @version	2.6.18
 */
class SportsPress_Default_Nationality {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_filter( 'sportspress_general_options', array( $this, 'add_general_options' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_DEFAULT_NATIONALITY_VERSION' ) )
			define( 'SP_DEFAULT_NATIONALITY_VERSION', '2.6.18' );

		if ( !defined( 'SP_DEFAULT_NATIONALITY_URL' ) )
			define( 'SP_DEFAULT_NATIONALITY_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_DEFAULT_NATIONALITY_DIR' ) )
			define( 'SP_DEFAULT_NATIONALITY_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add option to SportsPress General Settings.
	 */
	public function add_general_options( $settings ) {
		$countries[''] = __( '&mdash; None &mdash;', 'sportspress' );
		$sp_countries = new SP_Countries();
		$countries = array_merge ( $countries, $sp_countries->countries );

		$settings[]=array(
					'title'     => __( 'Default Nationality', 'sportspress' ),
					'id'        => 'sportspress_default_nationality',
					'default'   => '',
					'type'      => 'select',
					'options'   => $countries,
				);
		return $settings;
	}
}

endif;

new SportsPress_Default_Nationality();
