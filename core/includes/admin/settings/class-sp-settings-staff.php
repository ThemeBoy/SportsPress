<?php
/**
 * SportsPress Staff Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Staff' ) ) :

/**
 * SP_Settings_Staff
 */
class SP_Settings_Staff extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'staff';
		$this->label = __( 'Staff', 'sportspress' );

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

		return apply_filters( 'sportspress_staff_settings', array(

			array(	'title' => __( 'Staff Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'staff_options' ),

			array(
				'title'     => __( 'Link', 'sportspress' ),
				'desc' 		=> __( 'Link staff', 'sportspress' ),
				'id' 		=> 'sportspress_link_staff',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array(
				'title'     => __( 'Nationality', 'sportspress' ),
				'desc' 		=> __( 'Display national flags', 'sportspress' ),
				'id' 		=> 'sportspress_staff_show_flags',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			),

			array( 'type' => 'sectionend', 'id' => 'staff_options' ),

		)); // End staff settings
	}
}

endif;

return new SP_Settings_Staff();
