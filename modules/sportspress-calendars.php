<?php
/*
Plugin Name: SportsPress Calendars
Plugin URI: http://themeboy.com/
Description: Add event calendars to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.8.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Calendars' ) ) :

/**
 * Main SportsPress Calendars Class
 *
 * @class SportsPress_Calendars
 * @version	1.8.3
 */
class SportsPress_Calendars {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_widgets', array( $this, 'include_widgets' ) );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_CALENDARS_VERSION' ) )
			define( 'SP_CALENDARS_VERSION', '1.8.3' );

		if ( !defined( 'SP_CALENDARS_URL' ) )
			define( 'SP_CALENDARS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_CALENDARS_DIR' ) )
			define( 'SP_CALENDARS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register calendars post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_calendar',
			apply_filters( 'sportspress_register_post_type_calendar',
				array(
					'labels' => array(
						'name' 					=> __( 'Calendars', 'sportspress' ),
						'singular_name' 		=> __( 'Calendar', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Calendar', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Calendar', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Calendar', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_calendar',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_calendar_slug', 'calendar' ) ),
					'supports' 				=> array( 'title', 'author', 'thumbnail' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'show_in_menu' => 'edit.php?post_type=sp_event',
					'show_in_admin_bar' 	=> true,
				)
			)
		);
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_seasondiv', 'sp_calendar', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_calendar', 'side' );
		remove_meta_box( 'sp_venuediv', 'sp_calendar', 'side' );
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-calendar.php' );
	}

	/**
	 * Add widgets.
	 *
	 * @return array
	 */
	public function include_widgets() {
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-event-calendar.php' );
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-event-list.php' );
		include_once( SP()->plugin_path() . '/includes/widgets/class-sp-widget-event-blocks.php' );
	}

	/**
	 * Add meta boxes to calendars.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_calendar'] = array(
			'shortcode' => array(
				'title' => __( 'Shortcode', 'sportspress' ),
				'output' => 'SP_Meta_Box_Calendar_Shortcode::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'feeds' => array(
				'title' => __( 'Feeds', 'sportspress' ),
				'output' => 'SP_Meta_Box_Calendar_Feeds::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'format' => array(
				'title' => __( 'Layout', 'sportspress' ),
				'save' => 'SP_Meta_Box_Calendar_Format::save',
				'output' => 'SP_Meta_Box_Calendar_Format::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'details' => array(
				'title' => __( 'Details', 'sportspress' ),
				'save' => 'SP_Meta_Box_Calendar_Details::save',
				'output' => 'SP_Meta_Box_Calendar_Details::output',
				'context' => 'side',
				'priority' => 'default',
			),
			'data' => array(
				'title' => __( 'Events', 'sportspress' ),
				'save' => 'SP_Meta_Box_Calendar_Data::save',
				'output' => 'SP_Meta_Box_Calendar_Data::output',
				'context' => 'normal',
				'priority' => 'high',
			),
			'editor' => array(
				'title' => __( 'Description', 'sportspress' ),
				'output' => 'SP_Meta_Box_Calendar_Editor::output',
				'context' => 'normal',
				'priority' => 'low',
			),
		);
		return $meta_boxes;
	}

	/**
	 * Add shortcodes.
	 *
	 * @return array
	 */
	public function add_shortcodes( $shortcodes ) {
		$shortcodes['event'][] = 'calendar';
		$shortcodes['event'][] = 'list';
		$shortcodes['event'][] = 'blocks';
		return $shortcodes;
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Event List', 'sportspress' ), 'type' => 'title', 'id' => 'event_list_options' ),
			),

			apply_filters( 'sportspress_event_list_options', array(
				array(
					'title'     => __( 'Title', 'sportspress' ),
					'desc' 		=> __( 'Display calendar title', 'sportspress' ),
					'id' 		=> 'sportspress_event_list_show_title',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_event_list_show_logos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Title Format', 'sportspress' ),
					'id'        => 'sportspress_event_list_title_format',
					'default'   => 'title',
					'type'      => 'select',
					'options'   => array(
						'title' => __( 'Title', 'sportspress' ),
						'teams' => __( 'Teams', 'sportspress' ),
						'homeaway' => sprintf( '%s | %s', __( 'Home', 'sportspress' ), __( 'Away', 'sportspress' ) ),
					),
				),

				array(
					'title'     => __( 'Time/Results Format', 'sportspress' ),
					'id'        => 'sportspress_event_list_time_format',
					'default'   => 'combined',
					'type'      => 'select',
					'options'   => array(
						'combined' => __( 'Combined', 'sportspress' ),
						'separate' => __( 'Separate', 'sportspress' ),
						'time' => __( 'Time Only', 'sportspress' ),
						'results' => __( 'Results Only', 'sportspress' ),
					),
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_event_list_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_event_list_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			)),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_list_options' ),
				array( 'title' => __( 'Event Blocks', 'sportspress' ), 'type' => 'title', 'id' => 'event_blocks_options' ),
			),

			apply_filters( 'sportspress_event_blocks_options', array(
				array(
					'title'     => __( 'Title', 'sportspress' ),
					'desc' 		=> __( 'Display calendar title', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_show_title',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Details', 'sportspress' ),
					'desc' 		=> __( 'Display competition', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_show_league',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'start',
				),

				array(
					'desc' 		=> __( 'Display season', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_show_season',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Display venue', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_show_venue',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_event_blocks_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			)),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_list_options' ),
			)
		);
		return $settings;
	}
}

endif;

if ( get_option( 'sportspress_load_calendars_module', 'yes' ) == 'yes' ) {
	new SportsPress_Calendars();
}
