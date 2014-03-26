<?php
/**
 * SportsPress Text Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Text' ) ) :

/**
 * SP_Settings_Text
 */
class SP_Settings_Text extends SP_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'text';
		$this->label = __( 'Text', 'sportspress' );

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
		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'sportspress_tax_classes' ) ) ) );
		$classes_options = array();
		if ( $tax_classes )
			foreach ( $tax_classes as $class )
				$classes_options[ sanitize_title( $class ) ] = esc_html( $class );

		$settings = array( array(	'title' => __( 'Text Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'text_options' ) );

		$this->strings =& SP()->text->strings;
		foreach ( $this->strings as $string ):
			$settings[] = array(
				'title'   => $string,
				'id'      => 'sportspress_text_' . sanitize_title( $string ),
				'default' => '',
				'placeholder' => $string,
				'type'    => 'text',
			);
		endforeach;

		$settings[] = array( 'type' => 'sectionend', 'id' => 'text_options' );

		return apply_filters( 'sportspress_event_settings', $settings ); // End text settings
	}

	/**
	 * Output the settings
	 */
	public function output() {
		global $current_section;

		$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option('sportspress_tax_classes' ) ) ) );

		if ( $current_section == 'standard' || in_array( $current_section, array_map( 'sanitize_title', $tax_classes ) ) ) {
 			$this->output_tax_rates();
 		} else {
			$settings = $this->get_settings();

			SP_Admin_Settings::output_fields( $settings );
		}
	}

}

endif;

return new SP_Settings_Text();