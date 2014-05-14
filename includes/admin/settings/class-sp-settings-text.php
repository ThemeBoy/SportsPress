<?php
/**
 * SportsPress Text Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.8
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

		foreach ( $strings as $key => $value ):
			$settings[] = array(
				'title'   => $value,
				'id'      => 'sportspress_' . $key . '_text',
				'default' => '',
				'placeholder' => $value,
				'type'    => 'text',
			);
		endforeach;

		$settings[] = array( 'type' => 'sectionend', 'id' => 'text_options' );

		return apply_filters( 'sportspress_text_settings', $settings ); // End event settings
	}
}

endif;

return new SP_Settings_Text();
