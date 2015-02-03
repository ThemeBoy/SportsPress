<?php
/**
 * SportsPress Text Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Text' ) ) :

/**
 * SP_Settings_Text
 */
class SP_Settings_Text extends SP_Settings_Page {

	/**
	 * Constructor
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

		$settings = array(
			array( 'title' => __( 'Text', 'sportspress' ), 'type' => 'title', 'desc' => __( 'The following options affect how words are displayed on the frontend.', 'sportspress' ), 'id' => 'text_options' ),
		);

		$strings = sp_get_text_options();
		$options = get_option( 'sportspress_text' );

		$options = array();
		foreach ( $strings as $string ):
			$options[] = array(
				'title' 		=> $string,
				'id' 			=> 'sportspress_text[' . $string . ']',
				'default' 		=> '',
				'placeholder' 	=> $string,
				'value' 		=> sp_array_value( $options, $string, null ),
				'type' 			=> 'text',
			);
		endforeach;

		$settings = array_merge( $settings, apply_filters( 'sportspress_text_options', $options ), array(
			array( 'type' => 'sectionend', 'id' => 'text_options' )
		));

		return apply_filters( 'sportspress_text_settings', $settings ); // End event settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		if ( isset( $_POST['sportspress_text'] ) )
	    	update_option( 'sportspress_text', $_POST['sportspress_text'] );
	}
}

endif;

return new SP_Settings_Text();
