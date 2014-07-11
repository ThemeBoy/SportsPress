<?php
/**
 * SportsPress Settings Page/Tab
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Page' ) ) :

/**
 * SP_Settings_Page
 */
class SP_Settings_Page {

	protected $id    = '';
	protected $label = '';

	/**
	 * Add this page to settings
	 */
	public function add_settings_page( $pages ) {
		$pages[ $this->id ] = $this->label;

		return $pages;
	}

	/**
	 * Get settings array
	 *
	 * @return array
	 */
	public function get_settings() {
		return array();
	}

	/**
	 * Output the settings
	 */
	public function output() {
		$settings = $this->get_settings();

		SP_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings();
		SP_Admin_Settings::save_fields( $settings );

		 if ( $current_section )
	    	do_action( 'sportspress_update_options_' . $this->id . '_' . $current_section );
	}
}

endif;
