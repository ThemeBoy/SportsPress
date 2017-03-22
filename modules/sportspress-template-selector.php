<?php
/*
Plugin Name: SportsPress Template Selector
Plugin URI: http://themeboy.com/
Description: Add a template selector to SportsPress post types.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Template_Selector' ) ) :

/**
 * Main SportsPress Template Selector Class
 *
 * @class SportsPress_Template_Selector
 * @version	2.3
 */
class SportsPress_Template_Selector {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'sportspress_event_options', array( $this, 'event_options' ) );
		add_filter( 'sportspress_calendar_options', array( $this, 'calendar_options' ) );
		add_filter( 'sportspress_team_options', array( $this, 'team_options' ) );
		add_filter( 'sportspress_table_options', array( $this, 'table_options' ) );
		add_filter( 'sportspress_player_options', array( $this, 'player_options' ) );
		add_filter( 'sportspress_player_list_options', array( $this, 'list_options' ) );
		add_filter( 'sportspress_staff_options', array( $this, 'staff_options' ) );
		add_filter( 'sportspress_post_type_options', array( $this, 'post_type_options' ), 10, 2 );
		add_filter( 'sportspress_event_settings', array( $this, 'add_event_settings' ), 9);
		add_filter( 'template_include', array( $this, 'template_loader' ), 99 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TEMPLATE_SELECTOR_VERSION' ) )
			define( 'SP_TEMPLATE_SELECTOR_VERSION', '2.3' );

		if ( !defined( 'SP_TEMPLATE_SELECTOR_URL' ) )
			define( 'SP_TEMPLATE_SELECTOR_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TEMPLATE_SELECTOR_DIR' ) )
			define( 'SP_TEMPLATE_SELECTOR_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add option to event post type.
	*/
	public function event_options( $options ) {
		return $this->options( $options, 'event' );
	}

	/**
	 * Add option to calendar post type.
	*/
	public function calendar_options( $options ) {
		return $this->options( $options, 'calendar' );
	}

	/**
	 * Add option to team post type.
	*/
	public function team_options( $options ) {
		return $this->options( $options, 'team' );
	}

	/**
	 * Add option to league table post type.
	*/
	public function table_options( $options ) {
		return $this->options( $options, 'table' );
	}

	/**
	 * Add option to player post type.
	*/
	public function player_options( $options ) {
		return $this->options( $options, 'player' );
	}

	/**
	 * Add option to player list post type.
	*/
	public function list_options( $options ) {
		return $this->options( $options, 'list' );
	}

	/**
	 * Add option to staff post type.
	*/
	public function staff_options( $options ) {
		return $this->options( $options, 'staff' );
	}

	/**
	 * Filter for other post types.
	*/
	public function post_type_options( $options = array(), $post_type = null ) {
		if ( null == $post_type ) {
			return $options;
		}

		return $this->options( $options, $post_type );
	}

	/**
	 * Add template option.
	*/
	public function options( $options, $post_type ) {
		// Get page templates from current theme
		$templates = wp_get_theme()->get_page_templates( get_post() );

		// Sort options alphabetically
		asort( $templates );

		// Add default option
		$templates = array_merge( array( 'default' => __( 'Default Template', 'sportspress' ) ), $templates );

		$options = array_merge( array(
			array(
				'title'     => __( 'Template', 'sportspress' ),
				'id'        => 'sportspress_' . $post_type . '_page_template',
				'default'   => 'default',
				'type'      => 'select',
				'options'   => $templates,
			),
		), $options );

		return $options;
	}

	/**
	 * Load page template.
	*/
	public function template_loader( $template ) {
		if ( is_single() ) {
			$post_type = get_post_type();

			if ( is_sp_post_type( $post_type ) ) {
				$option = get_option( 'sportspress_' . str_replace( 'sp_', '', $post_type ) . '_page_template', 'default' );
				if ( 'default' !== $option ) {
					$new_template = locate_template( array( $option ) );
					if ( '' != $new_template ) {
						return $new_template ;
					}
				}
			}
		}

		return $template;
	}

	/**
	 * Add event settings.
	 *
	 * @return array
	 */
	public function add_event_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Calendars', 'sportspress' ), 'type' => 'title', 'id' => 'calendar_options' ),
			),

			apply_filters( 'sportspress_calendar_options', array() ),

			array(
				array( 'type' => 'sectionend', 'id' => 'calendar_options' ),
			)
		);
		return $settings;
	}
}

endif;

new SportsPress_Template_Selector();
