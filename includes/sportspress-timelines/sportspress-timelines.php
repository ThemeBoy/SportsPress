<?php
/*
Plugin Name: SportsPress Timelines
Plugin URI: http://tboy.co/pro
Description: Add timelines to SportsPress events.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Timelines' ) ) :

/**
 * Main SportsPress Timelines Class
 *
 * @class SportsPress_Timelines
 * @version	2.6
 *
 */
class SportsPress_Timelines {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'sportspress_event_templates', array( $this, 'templates' ) );
		add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_TIMELINES_VERSION' ) )
			define( 'SP_TIMELINES_VERSION', '2.6' );

		if ( !defined( 'SP_TIMELINES_URL' ) )
			define( 'SP_TIMELINES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TIMELINES_DIR' ) )
			define( 'SP_TIMELINES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add templates to event layout.
	 *
	 * @return array
	 */
	public function templates( $templates = array() ) {
		$templates['timeline'] = array(
			'title' => __( 'Timeline', 'sportspress' ),
			'option' => 'sportspress_event_show_timeline',
			'action' => array( $this, 'output' ),
			'default' => 'yes',
		);
		
		return $templates;
	}

	/**
	 * Output timeline.
	 *
	 * @access public
	 * @return void
	 */
	public function output() {
		// Get timelines format option
		$timelines_format = get_option( 'sportspress_timelines_format', 'horizontal' );
		if ( 'horizontal' === $timelines_format ) {
			sp_get_template( 'event-timeline.php', array(), '', SP_TIMELINES_DIR . 'templates/' );
		}else{
			sp_get_template( 'event-timeline-vertical.php', array(), '', SP_TIMELINES_DIR . 'templates/' );
		}
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-timelines'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TIMELINES_URL ) . 'css/sportspress-timelines.css',
			'deps'    => 'sportspress-general',
			'version' => SP_TIMELINES_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Timeline', 'sportspress' ),
			__( 'KO', 'sportspress' ),
			__( 'FT', 'sportspress' ),
		) );
	}
	
	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Timelines', 'sportspress' ), 'type' => 'title', 'id' => 'timelines_options' ),
			),

			apply_filters( 'sportspress_timelines_options', array(
				array(
					'title' 	=> __( 'Layout', 'sportspress' ),
					'id' 		=> 'sportspress_timelines_format',
					'default'	=> 'horizontal',
					'type' 		=> 'radio',
					'options' => array(
						'horizontal'=> __( 'Horizontal', 'sportspress' ),
						'vertical'	=> __( 'Vertical', 'sportspress' ),
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'timelines_options' ),
			)
		);
		return $settings;
	}
}

endif;

if ( get_option( 'sportspress_load_timelines_module', 'yes' ) == 'yes' ) {
	new SportsPress_Timelines();
}
