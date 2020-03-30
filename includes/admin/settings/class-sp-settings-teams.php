<?php
/**
 * SportsPress Team Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version		2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Teams' ) ) :

/**
 * SP_Settings_Teams
 */
class SP_Settings_Teams extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'teams';
		$this->label = __( 'Teams', 'sportspress' );
		$this->template  = 'team';

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_team_layout', array( $this, 'layout_setting' ) );
		add_action( 'sportspress_admin_field_team_tabs', array( $this, 'tabs_setting' ) );
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
				array(	'title' => __( 'Team Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'team_options' ),
			),

			apply_filters( 'sportspress_team_options', array(
				array( 'type' 	=> 'team_layout' ),

				array( 'type' 	=> 'team_tabs' ),

				array(
					'title'     => __( 'Staff', 'sportspress' ),
					'desc' 		=> __( 'Link staff', 'sportspress' ),
					'id' 		=> 'sportspress_team_link_staff',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Link', 'sportspress' ),
					'desc' 		=> __( 'Link teams', 'sportspress' ),
					'id' 		=> 'sportspress_link_teams',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Venue', 'sportspress' ),
					'desc' 		=> __( 'Link venues', 'sportspress' ),
					'id' 		=> 'sportspress_team_link_venues',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Visit Site', 'sportspress' ),
					'desc' 		=> __( 'Open link in a new window/tab', 'sportspress' ),
					'id' 		=> 'sportspress_team_site_target_blank',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
				
				array(
						'title'     => __( 'Comments', 'sportspress' ),
						'desc' 		=> __( 'Allow people to post comments on Team page', 'sportspress' ),
						'id' 		=> 'sportspress_team_comment_status',
						'default'	=> 'no',
						'type' 		=> 'checkbox',
					),
			)),

			array(
				array( 'type' => 'sectionend', 'id' => 'team_options' ),
			)

		);

		return apply_filters( 'sportspress_team_settings', $settings );
	}
}

endif;

return new SP_Settings_Teams();
