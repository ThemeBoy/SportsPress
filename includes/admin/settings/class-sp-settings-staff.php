<?php
/**
 * SportsPress Staff Settings
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin
 * @version     2.1.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Settings_Staff' ) ) :

	/**
	 * SP_Settings_Staff
	 */
	class SP_Settings_Staff extends SP_Settings_Page {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->id       = 'staff';
			$this->label    = esc_attr__( 'Staff', 'sportspress' );
			$this->template = 'staff';

			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_admin_field_staff_layout', array( $this, 'layout_setting' ) );
			add_action( 'sportspress_admin_field_staff_tabs', array( $this, 'tabs_setting' ) );
			add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		}

		/**
		 * Get settings array
		 *
		 * @return array
		 */
		public function get_settings() {

			return apply_filters(
				'sportspress_staff_settings',
				array_merge(
					array(
						array(
							'title' => esc_attr__( 'Staff Options', 'sportspress' ),
							'type'  => 'title',
							'desc'  => '',
							'id'    => 'staff_options',
						),
					),
					apply_filters(
						'sportspress_staff_options',
						array(
							array( 'type' => 'staff_layout' ),

							array( 'type' => 'staff_tabs' ),

							array(
								'title'   => esc_attr__( 'Link', 'sportspress' ),
								'desc'    => esc_attr__( 'Link staff', 'sportspress' ),
								'id'      => 'sportspress_link_staff',
								'default' => 'yes',
								'type'    => 'checkbox',
							),

							array(
								'title'         => esc_attr__( 'Details', 'sportspress' ),
								'desc'          => esc_attr__( 'Nationality', 'sportspress' ),
								'id'            => 'sportspress_staff_show_nationality',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => 'start',
							),

							array(
								'desc'          => esc_attr__( 'Current Team', 'sportspress' ),
								'id'            => 'sportspress_staff_show_current_teams',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => '',
							),

							array(
								'desc'          => esc_attr__( 'Past Teams', 'sportspress' ),
								'id'            => 'sportspress_staff_show_past_teams',
								'default'       => 'yes',
								'type'          => 'checkbox',
								'checkboxgroup' => 'end',
							),

							array(
								'title'   => esc_attr__( 'Nationality', 'sportspress' ),
								'desc'    => esc_attr__( 'Display national flags', 'sportspress' ),
								'id'      => 'sportspress_staff_show_flags',
								'default' => 'yes',
								'type'    => 'checkbox',
							),
						)
					),
					array(
						array(
							'type' => 'sectionend',
							'id'   => 'staff_options',
						),
					)
				)
			); // End staff settings
		}
	}

endif;

return new SP_Settings_Staff();
