<?php
/**
 * SportsPress General Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_General' ) ) :

/**
 * SP_Admin_Settings_General
 */
class SP_Settings_General extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'general';
		$this->label = __( 'General', 'sportspress' );

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
		return apply_filters( 'sportspress_general_settings', array(

			array( 'title' => __( 'General Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

			array(
				'title'     => __( 'Tables', 'sportspress' ),
				'desc' 		=> __( 'Responsive', 'sportspress' ),
				'id' 		=> 'sportspress_tables_responsive',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'	=> 'start',
				'autoload'  => false
			),

			array(
				'desc' 		=> __( 'Sortable', 'sportspress' ),
				'id' 		=> 'sportspress_tables_sortable',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
				'show_if_checked' => 'option',
			),

			array( 'type' => 'sectionend', 'id' => 'general_options' ),

		)); // End general settings
	}
}

endif;

return new SP_Settings_General();
