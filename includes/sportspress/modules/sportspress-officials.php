<?php
/*
Plugin Name: SportsPress Officials
Plugin URI: http://themeboy.com/
Description: Add officials to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Officials' ) ) :

/**
 * Main SportsPress Officials Class
 *
 * @class SportsPress_Officials
 * @version	2.5
 */
class SportsPress_Officials {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'sportspress_after_register_taxonomy', array( $this, 'register_taxonomy' ) );
		add_action( 'sportspress_after_register_post_type', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );
		add_action( 'sportspress_event_list_head_row', array( $this, 'event_list_head_row' ) );
		add_action( 'sportspress_event_list_row', array( $this, 'event_list_row' ), 10, 2 );
		add_action( 'sportspress_calendar_data_meta_box_table_head_row', array( $this, 'calendar_meta_head_row' ) );
		add_action( 'sportspress_calendar_data_meta_box_table_row', array( $this, 'calendar_meta_row' ), 10, 2 );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_calendar_columns', array( $this, 'calendar_columns' ) );
		add_filter( 'sportspress_after_event_template', array( $this, 'add_event_template' ), 30 );
		add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_primary_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_OFFICIALS_VERSION' ) )
			define( 'SP_OFFICIALS_VERSION', '2.5' );

		if ( !defined( 'SP_OFFICIALS_URL' ) )
			define( 'SP_OFFICIALS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_OFFICIALS_DIR' ) )
			define( 'SP_OFFICIALS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register officials taxonomy
	 */
	public static function register_taxonomy() {
		$labels = array(
			'name' => __( 'Duties', 'sportspress' ),
			'singular_name' => __( 'Duty', 'sportspress' ),
			'all_items' => __( 'All', 'sportspress' ),
			'edit_item' => __( 'Edit Duty', 'sportspress' ),
			'view_item' => __( 'View', 'sportspress' ),
			'update_item' => __( 'Update', 'sportspress' ),
			'add_new_item' => __( 'Add New', 'sportspress' ),
			'new_item_name' => __( 'Name', 'sportspress' ),
			'parent_item' => __( 'Parent', 'sportspress' ),
			'parent_item_colon' => __( 'Parent:', 'sportspress' ),
			'search_items' =>  __( 'Search', 'sportspress' ),
			'not_found' => __( 'No results found.', 'sportspress' ),
		);
		$args = apply_filters( 'sportspress_register_taxonomy_duty', array(
			'label' => __( 'Duties', 'sportspress' ),
			'labels' => $labels,
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
			'hierarchical' => true,
			'rewrite' => array( 'slug' => get_option( 'sportspress_duty_slug', 'duty' ) ),
			'show_in_rest' => true,
			'rest_controller_class' => 'SP_REST_Terms_Controller',
			'rest_base' => 'duties',
		) );
		$object_types = apply_filters( 'sportspress_duty_object_types', array( 'sp_official' ) );
		register_taxonomy( 'sp_duty', $object_types, $args );
		foreach ( $object_types as $object_type ):
			register_taxonomy_for_object_type( 'sp_duty', $object_type );
		endforeach;
	}

	/**
	 * Register officials post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_official',
			apply_filters( 'sportspress_register_post_type_official',
				array(
					'labels' => array(
						'name' 					=> __( 'Officials', 'sportspress' ),
						'singular_name' 		=> __( 'Official', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Official', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Official', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View Official', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
						'featured_image'		=> __( 'Photo', 'sportspress' ),
 						'set_featured_image' 	=> __( 'Select Photo', 'sportspress' ),
 						'remove_featured_image' => __( 'Remove Photo', 'sportspress' ),
 						'use_featured_image' 	=> __( 'Select Photo', 'sportspress' ),
					),
					'public' 				=> true,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_staff',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> true,
					'exclude_from_search' 	=> false,
					'hierarchical' 			=> false,
					'rewrite' 				=> array( 'slug' => get_option( 'sportspress_official_slug', 'official' ) ),
					'supports' 				=> array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> true,
					'menu_icon' 			=> 'dashicons-flag',
					'show_in_rest' 			=> true,
					'rest_controller_class' => 'SP_REST_Posts_Controller',
					'rest_base' 			=> 'officials',
				)
			)
		);
	}

	/**
	 * Remove meta boxes.
	 */
	public function remove_meta_boxes() {
		remove_meta_box( 'sp_dutydiv', 'sp_official', 'side' );
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-official.php' );
	}

	/**
	 * Create REST API routes.
	 */
	public function create_rest_routes() {
		$controller = new SP_REST_Posts_Controller( 'sp_official' );
		$controller->register_routes();
	}

	/**
	 * Register REST API fields.
	 */
	public function register_rest_fields() {
		register_rest_field( 'sp_official',
			'data',
			array(
				'get_callback'    => 'SP_REST_API::get_post_data',
				'update_callback' => 'SP_REST_API::update_post_meta_arrays',
				'schema'          => array(
					'description'     => __( 'Official', 'sportspress' ),
					'type'            => 'array',
					'context'         => array( 'view', 'edit' ),
					'arg_options'     => array(
						'sanitize_callback' => 'rest_sanitize_request_arg',
					),
				),
			)
		);
	}

	/**
	 * Event list head row.
	 */
	public function event_list_head_row( $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'officials', $usecolumns ) ) {
			$duties = get_terms( array(
			  'taxonomy' => 'sp_duty',
			  'hide_empty' => false,
			  'orderby' => 'slug',
			) );

			if ( empty( $duties ) ) return;

			foreach ( $duties as $duty ) {
				?>
				<th class="data-officials">
					<?php echo $duty->name; ?>
				</th>
				<?php
			}
		}
	}

	/**
	 * Event list row.
	 */
	public function event_list_row( $event, $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'officials', $usecolumns ) ) {
			$event = new SP_Event( $event );
			$appointments = $event->appointments( true );
			unset( $appointments[0] );

			foreach ( $appointments as $officials ) {
				?>
				<td class="data-officials">
					<?php echo implode( '<br>', $officials ); ?>
				</td>
				<?php
			}
		}
	}

	/**
	 * Calendar meta box table head row.
	 */
	public function calendar_meta_head_row( $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'officials', $usecolumns ) ) {
			$duties = get_terms( array(
			  'taxonomy' => 'sp_duty',
			  'hide_empty' => false,
			  'orderby' => 'slug',
			) );

			if ( empty( $duties ) ) return;

			foreach ( $duties as $duty ) {
				?>
				<th class="column-officials">
					<label for="sp_columns_officials">
						<?php echo $duty->name; ?>
					</label>
				</th>
				<?php
			}
		}
	}

	/**
	 * Calendar meta box table row.
	 */
	public function calendar_meta_row( $event, $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'officials', $usecolumns ) ) {
			$event = new SP_Event( $event );
			$appointments = $event->appointments( true, '&mdash;' );
			unset( $appointments[0] );

			foreach ( $appointments as $officials ) {
				?>
				<td>
					<?php echo implode( '<br>', $officials ); ?>
				</td>
				<?php
			}
		}
	}

	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_event']['officials'] = array(
			'title' => __( 'Officials', 'sportspress' ),
			'output' => 'SP_Meta_Box_Event_Officials::output',
			'save' => 'SP_Meta_Box_Event_Officials::save',
			'context' => 'side',
			'priority' => 'default',
		);
		$meta_boxes['sp_official'] = array(
			'details' => array(
				'title' => __( 'Details', 'sportspress' ),
				'save' => 'SP_Meta_Box_Official_Details::save',
				'output' => 'SP_Meta_Box_Official_Details::output',
				'context' => 'side',
				'priority' => 'default',
			),
		);
		return $meta_boxes;
	}

	/**
	 * Add calendar columns.
	 *
	 * @return array
	 */
	public function calendar_columns( $columns = array() ) {
		$columns['officials'] = __( 'Officials', 'sportspress' );
		return $columns;
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		return array_merge( $settings,
			array(
				array( 'title' => __( 'Officials', 'sportspress' ), 'type' => 'title', 'id' => 'table_options' ),
			),

			apply_filters( 'sportspress_table_options', array(
				array(
					'title'     => __( 'Duty', 'sportspress' ),
					'desc' 		=> __( 'Display title', 'sportspress' ),
					'id' 		=> 'sportspress_table_show_title',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_table_show_logos',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Pagination', 'sportspress' ),
					'desc' 		=> __( 'Paginate', 'sportspress' ),
					'id' 		=> 'sportspress_table_paginated',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
				),
				
				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_table_rows',
					'class' 	=> 'small-text',
					'default'	=> '10',
					'desc' 		=> __( 'teams', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
				
				array(
					'title' 	=> __( 'Form', 'sportspress' ),
					'id' 		=> 'sportspress_form_limit',
					'class' 	=> 'small-text',
					'default'	=> '5',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),

				array(
					'title'     => __( 'Pos', 'sportspress' ),
					'desc' 		=> __( 'Always increment', 'sportspress' ),
					'id' 		=> 'sportspress_table_increment',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title'     => __( 'Tiebreaker', 'sportspress' ),
					'id'        => 'sportspress_table_tiebreaker',
					'default'   => 'none',
					'type'      => 'select',
					'options'   => array(
						'none' => __( 'None', 'sportspress' ),
						'h2h' => __( 'Head to head', 'sportspress' ),
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'table_options' ),
			)
		);
	}

	/**
	 * Add event template.
	 *
	 * @return array
	 */
	public function add_event_template( $templates ) {
		return array_merge( $templates, array(
			'officials' => array(
				'title' => __( 'Officials', 'sportspress' ),
				'option' => 'sportspress_event_show_officials',
				'action' => 'sportspress_output_event_officials',
				'default' => 'yes',
			),
		) );
	}

	/**
	 * Add screen ids.
	 *
	 * @return array
	 */
	public function screen_ids( $ids ) {
		return array_merge( $ids, array(
			'sp_official',
			'edit-sp_official',
		) );
	}

	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_official';
		return $post_types;
	}

	public static function add_to_hierarchy( $hierarchy = array() ) {
		$hierarchy['sp_official'] = array();
		return $hierarchy;
	}
}

endif;

if ( get_option( 'sportspress_load_officials_module', 'no' ) == 'yes' ) {
	new SportsPress_Officials();
}
