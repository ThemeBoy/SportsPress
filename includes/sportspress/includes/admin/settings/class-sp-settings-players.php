<?php
/**
 * SportsPress Player Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     2.0
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
		
		$this->template  = 'player';
		$this->templates = SP()->templates->player;

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_player_layout', array( $this, 'layout_setting' ) );
		add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {

		$settings = array_merge(
			array(
				array(	'title' => __( 'Player Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'player_options' ),
			),

			apply_filters( 'sportspress_player_options', array(
				array( 'type' 	=> 'player_layout' ),

				array(
					'title'     => __( 'Link', 'sportspress' ),
					'desc' 		=> __( 'Link players', 'sportspress' ),
					'id' 		=> 'sportspress_link_players',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Details', 'sportspress' ),
					'desc' 		=> __( 'Nationality', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_nationality',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'start',
				),

				array(
					'desc' 		=> __( 'Position', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_positions',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Current Team', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_current_teams',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Past Teams', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_past_teams',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Competitions', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_leagues',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Seasons', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_seasons',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
				),

				array(
					'title' 	=> __( 'Columns', 'sportspress' ),
					'id' 		=> 'sportspress_player_columns',
					'default'	=> 'auto',
					'type' 		=> 'radio',
					'options' => array(
						'auto'		=> __( 'Auto', 'sportspress' ),
						'manual'	=> __( 'Manual', 'sportspress' ),
					),
				),

				array(
					'title' 	=> __( 'Statistics', 'sportspress' ),
					'id' 		=> 'sportspress_player_performance_sections',
					'default'	=> -1,
					'type' 		=> 'radio',
					'options' => array(
						-1	  => __( 'Combined', 'sportspress' ),
						0	  => __( 'Offense', 'sportspress' ) . ' &rarr; ' . __( 'Defense', 'sportspress' ),
						1	  => __( 'Defense', 'sportspress' ) . ' &rarr; ' . __( 'Offense', 'sportspress' ),
					),
				),

				array(
					'title'     => __( 'Total', 'sportspress' ),
					'desc' 		=> __( 'Display total', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_total',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Nationality', 'sportspress' ),
					'desc' 		=> __( 'Display national flags', 'sportspress' ),
					'id' 		=> 'sportspress_player_show_flags',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'player_options' ),
			)

		);

		return apply_filters( 'sportspress_player_settings', $settings );
	}
}

endif;

return new SP_Settings_Players();
