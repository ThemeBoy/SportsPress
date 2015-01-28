<?php
/*
Plugin Name: SportsPress League Tables
Plugin URI: http://themeboy.com/
Description: Add league tables to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_League_Tables' ) ) :

/**
 * Main SportsPress League Tables Class
 *
 * @class SportsPress_League_Tables
 * @version	1.6
 */
class SportsPress_League_Tables {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Mods
		add_filter( 'sportspress_team_settings', array( $this, 'add_options' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_LEAGUE_TABLES_VERSION' ) )
			define( 'SP_LEAGUE_TABLES_VERSION', '1.6' );

		if ( !defined( 'SP_LEAGUE_TABLES_URL' ) )
			define( 'SP_LEAGUE_TABLES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_LEAGUE_TABLES_DIR' ) )
			define( 'SP_LEAGUE_TABLES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add options to settings page.
	 *
	 * @return array
	 */
	public function add_options( $settings ) {
		return array_merge( $settings,

			array(
				array( 'title' => __( 'League Tables', 'sportspress' ), 'type' => 'title', 'id' => 'table_options' ),
			),

			apply_filters( 'sportspress_table_options', array(
				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_table_show_logos',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_table_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_table_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'teams', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'table_options' ),
			)

		);
	}
}

endif;

if ( get_option( 'sportspress_load_league_tables_module', 'yes' ) == 'yes' ) {
	new SportsPress_League_Tables();
}
