<?php
/**
 * SportsPress Tournament Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress Tournaments
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Tournaments' ) ) :

/**
 * SP_Settings_Tournaments
 */
class SP_Settings_Tournaments extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'tournaments';
		$this->label = __( 'Tournaments', 'sportspress' );

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

		return apply_filters( 'sportspress_tournament_settings', array(

			array( 'title' => __( 'Tournaments', 'sportspress' ), 'type' => 'title', 'id' => 'tournament_options' ),

			array(
				'title'     => __( 'Teams', 'sportspress' ),
				'desc' 		=> __( 'Display logos', 'sportspress' ),
				'id' 		=> 'sportspress_tournament_show_logos',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array( 'type' => 'sectionend', 'id' => 'tournament_options' ),

		)); // End tournament settings
	}
}

endif;

return new SP_Settings_Tournaments();
