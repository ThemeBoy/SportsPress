<?php
/**
 * SportsPress Player Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
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
				'title'     => __( 'Nationality', 'sportspress' ),
				'desc' 		=> __( 'Display national flags', 'sportspress' ),
				'id' 		=> 'sportspress_player_show_flags',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array(
				'title'     => __( 'Player Lists', 'sportspress' ),
				'desc' 		=> __( 'Link players', 'sportspress' ),
				'id' 		=> 'sportspress_list_link_players',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array( 'type' => 'sectionend', 'id' => 'player_options' ),

			array( 'title' => __( 'Text', 'sportspress' ), 'type' => 'title', 'desc' => __( 'The following options affect how words are displayed on the frontend.', 'sportspress' ), 'id' => 'text_options' ),

		);

		$strings = sp_get_text_options();

		foreach ( sp_array_value( $strings, 'player', array() ) as $key => $value ):
			$settings[] = array(
				'title'   => $value,
				'id'      => 'sportspress_player_' . $key . '_text',
				'default' => '',
				'placeholder' => $value,
				'type'    => 'text',
			);
		endforeach;

		$settings[] = array( 'type' => 'sectionend', 'id' => 'text_options' );

		return apply_filters( 'sportspress_event_settings', $settings ); // End player settings
	}
}

endif;

return new SP_Settings_Players();
