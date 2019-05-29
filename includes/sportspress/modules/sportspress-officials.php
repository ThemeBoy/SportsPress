<?php
/*
Plugin Name: SportsPress Officials
Plugin URI: http://themeboy.com/
Description: Add officials to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.15
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Officials' ) ) :

/**
 * Main SportsPress Officials Class
 *
 * @class SportsPress_Officials
 * @version	2.6.15
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
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_create_rest_routes', array( $this, 'create_rest_routes' ) );
		add_action( 'sportspress_register_rest_fields', array( $this, 'register_rest_fields' ) );
		add_action( 'sportspress_event_list_head_row', array( $this, 'event_list_head_row' ) );
		add_action( 'sportspress_event_list_row', array( $this, 'event_list_row' ), 10, 2 );
		add_action( 'sportspress_calendar_data_meta_box_table_head_row', array( $this, 'calendar_meta_head_row' ) );
		add_action( 'sportspress_calendar_data_meta_box_table_row', array( $this, 'calendar_meta_row' ), 10, 2 );
		add_action( 'sp_duty_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 1 );
		add_action( 'edited_sp_duty', array( $this, 'save_taxonomy_fields' ), 10, 1 );
		add_action( 'admin_menu', array( $this, 'duties_menu' ) );
		add_action( 'parent_file', array( $this, 'parent_file' ) );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_calendar_columns', array( $this, 'calendar_columns' ) );
		add_filter( 'sportspress_after_event_template', array( $this, 'add_event_template' ), 30 );
		add_filter( 'sportspress_event_options', array( $this, 'add_event_options' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_menu_items', array( $this, 'add_menu_item' ) );
		add_filter( 'sportspress_glance_items', array( $this, 'add_glance_item' ) );
		add_filter( 'sportspress_importers', array( $this, 'register_importer' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'sportspress_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_primary_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_importable_post_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_post_type_hierarchy', array( $this, 'add_to_hierarchy' ) );
		add_filter( 'manage_edit-sp_duty_columns', array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_sp_duty_custom_column', array( $this, 'taxonomy_column_value' ), 10, 3 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_OFFICIALS_VERSION' ) )
			define( 'SP_OFFICIALS_VERSION', '2.6.15' );

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
			'capabilities' => array(
				'manage_terms' => 'manage_sp_event_terms',
				'edit_terms' => 'edit_sp_event_terms',
				'delete_terms' => 'delete_sp_event_terms',
				'assign_terms' => 'assign_sp_event_terms',
			),
			'show_in_rest' => true,
			'rest_controller_class' => 'SP_REST_Terms_Controller',
			'rest_base' => 'duties',
		) );
		$object_types = apply_filters( 'sportspress_duty_object_types', array() );
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
					'capability_type' 		=> 'sp_event',
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
				'orderby' => 'meta_value_num',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'sp_order',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key' => 'sp_order',
						'compare' => 'EXISTS'
					),
				),
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
				'orderby' => 'meta_value_num',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'sp_order',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key' => 'sp_order',
						'compare' => 'EXISTS'
					),
				),
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
	 * Add event options.
	 *
	 * @return array
	 */
	public function add_event_options( $options ) {
		$options[] = array(
			'title'     => __( 'Officials', 'sportspress' ),
			'id'        => 'sportspress_event_officials_format',
			'default'   => 'table',
			'type'      => 'radio',
			'options'   => array(
				'table' => __( 'Table', 'sportspress' ),
				'list' => __( 'List', 'sportspress' ),
			),
		);
		return $options;
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Officials', 'sportspress' ),
		) );
	}

	/**
	 * Add menu item
	 */
	public function add_menu_item( $items ) {
		$items[] = 'edit.php?post_type=sp_official';
		return $items;
	}

	/**
	 * Add glance item
	 */
	public function add_glance_item( $items ) {
		$items[] = 'sp_official';
		return $items;
	}

	/**
	 * Register importer
	 */
	public function register_importer( $importers = array() ) {
		$importers['sp_official_csv'] = array(
			'name' => __( 'SportsPress Officials (CSV)', 'sportspress' ),
			'description' => __( 'Import <strong>officials</strong> from a csv file.', 'sportspress'),
			'callback' => array( $this, 'officials_importer' ),
		);
		return $importers;
	}

	/**
	 * Officials importer
	 */
	public function officials_importer() {
		SP_Admin_Importers::includes();

			require SP()->plugin_path() . '/includes/admin/importers/class-sp-official-importer.php';

			// Dispatch
			$importer = new SP_Official_Importer();
			$importer->dispatch();
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
			'sp_duty',
			'edit-sp_duty',
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

	/**
	 * Taxonomy columns.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function taxonomy_columns( $columns ) {
		$new_columns = array();
		
		if ( function_exists( 'get_term_meta' ) ) $new_columns['sp_order'] = __( 'Order', 'sportspress' );

		if ( array_key_exists('posts', $columns) ) {
		$new_columns['posts'] = $columns['posts'];

		unset( $columns['posts'] );
		}

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Edit taxonomy fields.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_taxonomy_fields( $term ) {
	 	$t_id = $term->term_id;
		?>
		<?php if ( function_exists( 'get_term_meta' ) ) { ?>
			<?php $order = get_term_meta( $t_id, 'sp_order', true ); ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="sp_order"><?php _e( 'Order', 'sportspress' ); ?></label></th>
				<td><input name="sp_order" class="sp-number-input" type="text" step="1" size="4" id="sp_order" value="<?php echo (int) $order; ?>"></td>
			</tr>
		<?php } ?>
	<?php
	}

	/**
	 * Save taxonomy fields.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @return void
	 */
	public function save_taxonomy_fields( $term_id ) {
		if ( function_exists( 'add_term_meta' ) ) {
			update_term_meta( $term_id, 'sp_order', (int) sp_array_value( $_POST, 'sp_order', 0 ) );
		}
	}

	/**
	 * Column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function taxonomy_column_value( $columns, $column, $id ) {

		if ( $column == 'sp_address' ) {

			$term_meta = get_option( "taxonomy_$id" );

			$address = ( isset( $term_meta['sp_address'] ) ? $term_meta['sp_address'] : '&mdash;' );

			$columns .= $address;

		} elseif ( $column == 'sp_sections' ) {
			
			$options = apply_filters( 'sportspress_performance_sections', array( 0 => __( 'Offense', 'sportspress' ), 1 => __( 'Defense', 'sportspress' ) ) );

			$sections = sp_get_term_sections( $id );
			
			$section_names = array();
			
			if ( is_array( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( array_key_exists( $section, $options ) ) {
						$section_names[] = $options[ $section ];
					}
				}
			}
			
			$columns .= implode( ', ', $section_names );

		} elseif ( $column == 'sp_order' ) {

			$columns = (int) get_term_meta( $id, 'sp_order', true );

		}

		return $columns;
	}

	/**
	 * Add menu item
	 */
	public function duties_menu() {
		add_submenu_page( 'edit.php?post_type=sp_official', __( 'Duties', 'sportspress' ), __( 'Duties', 'sportspress' ), 'manage_sp_event_terms', 'edit-tags.php?taxonomy=sp_duty');
	}

	/**
	 * Highlight parent menu item
	 */
	public function parent_file( $parent_file ) {
		global $current_screen;
		$taxonomy = $current_screen->taxonomy;

		if ( 'sp_duty' == $taxonomy )
			$parent_file = 'edit.php?post_type=sp_official';

		return $parent_file;
	}
}

endif;

if ( get_option( 'sportspress_load_officials_module', 'no' ) == 'yes' ) {
	new SportsPress_Officials();
}
