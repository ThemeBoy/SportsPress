<?php
/**
 * SportsPress Player Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Players' ) ) :

/**
 * SP_Settings_Players
 */
class SP_Settings_Players extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'players';
		$this->label = __( 'Players', 'sportspress' );

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		$settings = array(
			array(	'title' => __( 'Player Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'player_options' ),

			array(
				'title'     => __( 'Link', 'sportspress' ),
				'desc' 		=> __( 'Link players', 'sportspress' ),
				'id' 		=> 'sportspress_link_players',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array(
				'title'     => __( 'Nationality', 'sportspress' ),
				'desc' 		=> __( 'Display national flags', 'sportspress' ),
				'id' 		=> 'sportspress_player_show_flags',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array( 'type' => 'sectionend', 'id' => 'player_options' ),

			array( 'title' => __( 'Player Lists', 'sportspress' ), 'type' => 'title', 'id' => 'list_options' ),

			array(
				'title'     => __( 'Players', 'sportspress' ),
				'desc' 		=> __( 'Display photos', 'sportspress' ),
				'id' 		=> 'sportspress_list_show_photos',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
			),

			array(
				'title'     => __( 'Pagination', 'sportspress' ),
				'desc' 		=> __( 'Paginate', 'sportspress' ),
				'id' 		=> 'sportspress_list_paginated',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_list_rows',
				'class' 	=> 'small-text',
				'default'	=> '10',
				'desc' 		=> __( 'players', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'list_options' ),
		); // End player settings

		return apply_filters( 'sportspress_player_settings', $settings );
	}
}

endif;

return new SP_Settings_Players();
