<?php
/**
 * SportsPress Player Settings
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.6.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_Players' ) ) :

	/**
	 * SP_Settings_Players
	 */
	class SP_Settings_Players extends SP_Settings_Page {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id       = 'players';
			$this->label    = esc_attr__( 'Players', 'sportspress' );
			$this->template = 'player';

			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_admin_field_player_layout', array( $this, 'layout_setting' ) );
			add_action( 'sportspress_admin_field_player_tabs', array( $this, 'tabs_setting' ) );
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
					array(
						'title' => esc_attr__( 'Player Options', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'player_options',
					),
				),
				apply_filters(
					'sportspress_player_options',
					array(
						array( 'type' => 'player_layout' ),

						array( 'type' => 'player_tabs' ),

						array(
							'title'   => esc_attr__( 'Link', 'sportspress' ),
							'desc'    => esc_attr__( 'Link players', 'sportspress' ),
							'id'      => 'sportspress_link_players',
							'default' => 'yes',
							'type'    => 'checkbox',
						),

						array(
							'title'         => esc_attr__( 'Details', 'sportspress' ),
							'desc'          => esc_attr__( 'Squad Number', 'sportspress' ),
							'id'            => 'sportspress_player_show_number',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'start',
						),

						array(
							'desc'          => esc_attr__( 'Name', 'sportspress' ),
							'id'            => 'sportspress_player_show_name',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Nationality', 'sportspress' ),
							'id'            => 'sportspress_player_show_nationality',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Position', 'sportspress' ),
							'id'            => 'sportspress_player_show_positions',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Current Team', 'sportspress' ),
							'id'            => 'sportspress_player_show_current_teams',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Past Teams', 'sportspress' ),
							'id'            => 'sportspress_player_show_past_teams',
							'default'       => 'yes',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Leagues', 'sportspress' ),
							'id'            => 'sportspress_player_show_leagues',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => '',
						),

						array(
							'desc'          => esc_attr__( 'Seasons', 'sportspress' ),
							'id'            => 'sportspress_player_show_seasons',
							'default'       => 'no',
							'type'          => 'checkbox',
							'checkboxgroup' => 'end',
						),

						array(
							'title'   => esc_attr__( 'Nationality', 'sportspress' ),
							'desc'    => esc_attr__( 'Display national flags', 'sportspress' ),
							'id'      => 'sportspress_player_show_flags',
							'default' => 'yes',
							'type'    => 'checkbox',
						),
					)
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'player_options',
					),
				),
				array(
					array(
						'title' => esc_attr__( 'Statistics', 'sportspress' ),
						'type'  => 'title',
						'desc'  => '',
						'id'    => 'player_statistic_options',
					),

					array(
						'title'   => esc_attr__( 'Columns', 'sportspress' ),
						'id'      => 'sportspress_player_columns',
						'default' => 'auto',
						'type'    => 'radio',
						'options' => array(
							'auto'   => esc_attr__( 'Auto', 'sportspress' ),
							'manual' => esc_attr__( 'Manual', 'sportspress' ),
						),
					),

					array(
						'title'   => esc_attr__( 'Mode', 'sportspress' ),
						'id'      => 'sportspress_player_statistics_mode',
						'default' => 'values',
						'type'    => 'radio',
						'options' => array(
							'values' => esc_attr__( 'Values', 'sportspress' ),
							'icons'  => esc_attr__( 'Icons', 'sportspress' ),
						),
					),

					array(
						'title'   => esc_attr__( 'Categories', 'sportspress' ),
						'id'      => 'sportspress_player_performance_sections',
						'default' => -1,
						'type'    => 'radio',
						'options' => array(
							-1 => esc_attr__( 'Combined', 'sportspress' ),
							0  => esc_attr__( 'Offense', 'sportspress' ) . ' &rarr; ' . esc_attr__( 'Defense', 'sportspress' ),
							1  => esc_attr__( 'Defense', 'sportspress' ) . ' &rarr; ' . esc_attr__( 'Offense', 'sportspress' ),
						),
					),

					array(
						'title'         => esc_attr__( 'Display', 'sportspress' ),
						'desc'          => esc_attr__( 'Total', 'sportspress' ),
						'id'            => 'sportspress_player_show_total',
						'default'       => 'no',
						'type'          => 'checkbox',
						'checkboxgroup' => 'start',
					),

					array(
						'desc'          => esc_attr__( 'Career Total', 'sportspress' ),
						'id'            => 'sportspress_player_show_career_total',
						'default'       => 'no',
						'type'          => 'checkbox',
						'checkboxgroup' => 'end',
					),
				),
				apply_filters(
					'sportspress_player_statistic_options',
					array()
				),
				array(
					array(
						'type' => 'sectionend',
						'id'   => 'player_statistic_options',
					),
				)
			);

			return apply_filters( 'sportspress_player_settings', $settings );
		}
	}

endif;

return new SP_Settings_Players();
