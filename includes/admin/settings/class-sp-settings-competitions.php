<?php
/**
 * SportsPress Competitions Settings
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress_Branding
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Settings_Competitions' ) ) :

/**
 * SP_Settings_Competitions
 */
class SP_Settings_Competitions extends SP_Settings_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id    = 'competition';
		$this->label = __( 'Competitions', 'sportspress' );
		$this->template  = 'competition';

		add_filter( 'sportspress_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'sportspress_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'sportspress_admin_field_competitions_layout', array( $this, 'layout_setting' ) );
		add_action( 'sportspress_admin_field_competitions_tabs', array( $this, 'tabs_setting' ) );
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
				array(	'title' => __( 'Competitions Options', 'sportspress' ), 'type' => 'title','desc' => '', 'id' => 'competitions_options' ),
			),

			apply_filters( 'sportspress_competitions_options', array(
				array( 'type' 	=> 'competitions_layout' ),
				array( 'type' 	=> 'competitions_tabs' ),

			) ),
			array(
				array( 'type' => 'sectionend', 'id' => 'competitions_options' ),
			),
			array(
				array( 'title' => __( 'Events Options', 'sportspress' ), 'type' => 'title', 'desc' => '', 'id' => 'events_options' ),
			),
			apply_filters( 'sportspress_competition_event_template_options', array(
					array(
					'title'     => __( 'Events', 'sportspress' ),
					'id'        => 'sportspress_competition_events_format',
					'default'   => 'title',
					'type'      => 'select',
					'options'   => array(
						'blocks' 	=> __( 'Blocks', 'sportspress' ),
						'calendar' 	=> __( 'Calendar', 'sportspress' ),
						'list' 		=> __( 'List', 'sportspress' ),
					),
					),
				) ),
			array(
				array( 'type' => 'sectionend', 'id' => 'events_options' ),
			)
		) ;
		return apply_filters( 'sportspress_competitions_settings', $settings );
		// End Competition settings
	}

	/**
	 * Save settings
	 */
	public function save() {
		parent::save();
	}
}

endif;

return new SP_Settings_Competitions();
