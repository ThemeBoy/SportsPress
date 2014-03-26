<?php
/**
 * SportsPress Integration Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Integrations' ) ) :

/**
 * SP_Settings_Integrations
 */
class SP_Settings_Integrations extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'integration';
		$this->label = __( 'Integration', 'sportspress' );

		if ( isset( SP()->integrations ) && SP()->integrations->get_integrations() ) {
			add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
			add_action( 'sportspress_sections_' . $this->id, array( $this, 'output_sections' ) );
			add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'sportspress_settings_save_' . $this->id, array( $this, 'save' ) );
		}
	}

	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {
		global $current_section;

		$sections = array();

		$integrations = SP()->integrations->get_integrations();

		if ( ! $current_section )
			$current_section = current( $integrations )->id;

		foreach ( $integrations as $integration ) {
			$title = empty( $integration->method_title ) ? ucfirst( $integration->id ) : $integration->method_title;

			$sections[ strtolower( $integration->id ) ] = esc_html( $title );
		}

		return $sections;
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$integrations = SP()->integrations->get_integrations();

		if ( isset( $integrations[ $current_section ] ) )
			$integrations[ $current_section ]->admin_options();
	}
}

endif;

return new SP_Settings_Integrations();