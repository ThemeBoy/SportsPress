<?php
/*
Plugin Name: SportsPress Event Specs
Plugin URI: http://themeboy.com/
Description: Add event specs/stats to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.6.15
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Specs' ) ) :

/**
 * Main SportsPress Event Specs Class
 *
 * @class SportsPress_Event_Specs
 * @version	2.6.15
 */
class SportsPress_Event_Specs {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'sportspress_config_page', array( $this, 'sp_specs_config' ), 9 );
		add_action( 'sportspress_include_post_type_handlers', array( $this, 'include_post_type_handler' ) );
		add_action( 'sportspress_event_list_head_row', array( $this, 'event_list_head_row' ), 11 );
		add_action( 'sportspress_event_list_row', array( $this, 'event_list_row' ), 11, 2 );
		add_action( 'sportspress_event_blocks_after', array( $this, 'event_blocks_after' ), 11, 2 );

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_screen_ids', array( $this, 'screen_ids' ) );
		add_filter( 'sportspress_config_types', array( $this, 'add_post_type' ) );
		add_filter( 'sportspress_event_details', array( $this, 'event_details' ), 10, 2 );
		add_filter( 'sportspress_calendar_columns', array( $this, 'calendar_columns' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EVENT_SPECS_VERSION' ) )
			define( 'SP_EVENT_SPECS_VERSION', '2.6.15' );

		if ( !defined( 'SP_EVENT_SPECS_URL' ) )
			define( 'SP_EVENT_SPECS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_SPECS_DIR' ) )
			define( 'SP_EVENT_SPECS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Register event specs post type
	 */
	public static function register_post_type() {
		register_post_type( 'sp_spec',
			apply_filters( 'sportspress_register_post_type_spec',
				array(
					'labels' => array(
						'name' 					=> __( 'Event Specs', 'sportspress' ),
						'singular_name' 		=> __( 'Event Spec', 'sportspress' ),
						'add_new_item' 			=> __( 'Add New Event Spec', 'sportspress' ),
						'edit_item' 			=> __( 'Edit Event Spec', 'sportspress' ),
						'new_item' 				=> __( 'New', 'sportspress' ),
						'view_item' 			=> __( 'View', 'sportspress' ),
						'search_items' 			=> __( 'Search', 'sportspress' ),
						'not_found' 			=> __( 'No results found.', 'sportspress' ),
						'not_found_in_trash' 	=> __( 'No results found.', 'sportspress' ),
					),
					'public' 				=> false,
					'show_ui' 				=> true,
					'capability_type' 		=> 'sp_config',
					'map_meta_cap' 			=> true,
					'publicly_queryable' 	=> false,
					'exclude_from_search' 	=> true,
					'hierarchical' 			=> false,
					'supports' 				=> array( 'title', 'page-attributes', 'excerpt' ),
					'has_archive' 			=> false,
					'show_in_nav_menus' 	=> false,
					'can_export' 			=> false,
					'show_in_menu' 			=> false,
				)
			)
		);
	}

	/**
	 * Add screen ids.
	 *
	 * @return array
	 */
	public function screen_ids( $ids ) {
		return array_merge( $ids, array(
			'edit-sp_spec',
			'sp_spec',
		) );
	}
	
	public static function add_post_type( $post_types = array() ) {
		$post_types[] = 'sp_spec';
		return $post_types;
	}

	/**
	 * Conditonally load the class and functions only needed when viewing this post type.
	 */
	public function include_post_type_handler() {
		include_once( SP()->plugin_path() . '/includes/admin/post-types/class-sp-admin-cpt-spec.php' );
	}
	
	/**
	 * Display Event Specs Table at Config Page
	 * @return null
	 */
	public function sp_specs_config() {
		?>
	<table class="form-table">
		<tbody>
			<?php
			$args = array(
				'post_type' => 'sp_spec',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$data = get_posts( $args );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<?php _e( 'Event Specs', 'sportspress' ) ?>
					<p class="description"><?php _e( 'Add more details to an event.', 'sportspress' ); ?></p>
				</th>
			    <td class="forminp">
					<table class="widefat sp-admin-config-table">
						<thead>
							<tr>
								<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Variable', 'sportspress' ); ?></th>
								<th scope="col"><?php _e( 'Description', 'sportspress' ); ?></th>
								<th scope="col" class="edit"></th>
							</tr>
						</thead>
						<?php if ( $data ): $i = 0; foreach ( $data as $row ): ?>
							<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
								<td class="row-title"><?php echo $row->post_title; ?></td>
								<td><code><?php echo $row->post_name; ?></code></td>
								<td><p class="description"><?php echo $row->post_excerpt; ?></p></td>
								<td class="edit"><a class="button" href="<?php echo get_edit_post_link( $row->ID ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></s></td>
							</tr>
						<?php $i++; endforeach; else: ?>
							<tr class="alternate">
								<td colspan="4"><?php _e( 'No results found.', 'sportspress' ); ?></td>
							</tr>
						<?php endif; ?>
					</table>
					<div class="tablenav bottom">
						<a class="button alignleft" href="<?php echo admin_url( 'edit.php?post_type=sp_spec' ); ?>"><?php _e( 'View All', 'sportspress' ); ?></a>
						<a class="button button-primary alignright" href="<?php echo admin_url( 'post-new.php?post_type=sp_spec' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
						<br class="clear">
					</div>
				</td>
			</tr>
		</tbody>
	</table>
		<?php
	}

	/**
	 * Add meta boxes.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_spec'] = array(
				'details' => array(
					'title' => __( 'Specs', 'sportspress' ),
					'save' => 'SP_Meta_Box_Spec_Details::save',
					'output' => 'SP_Meta_Box_Spec_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			);
		$meta_boxes['sp_event']['specs'] = array(
					'title' => __( 'Specs', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Specs::save',
					'output' => 'SP_Meta_Box_Event_Specs::output',
					'context' => 'side',
					'priority' => 'default',
				);
		return $meta_boxes;
	}
	
	/**
	 * Add event details.
	 *
	 * @return array
	 */
	 public function event_details ( $data, $id ) {
		 
		$event = new SP_Event( $id );

		$specs_before = $event->specs( true );
		$specs_after = $event->specs( false );
		
		$data = array_merge( $specs_before, $data, $specs_after );
		
		return $data;
	 }
	 
	/**
	 * Add calendar columns.
	 *
	 * @return array
	 */
	public function calendar_columns( $columns = array() ) {
		$columns['event_specs'] = __( 'Event Specs', 'sportspress' );
		return $columns;
	}
	
	/**
	 * Event list head row.
	 */
	public function event_list_head_row( $usecolumns = array() ) {
		if ( sp_column_active( $usecolumns, 'event_specs' ) ) {
			$spec_labels = (array)sp_get_var_labels( 'sp_spec', null, false );

			if ( empty( $spec_labels ) ) return;

			foreach ( $spec_labels as $spec_label ) {
				?>
				<th class="data-specs">
					<?php echo $spec_label; ?>
				</th>
				<?php
			}
		}
	}

	/**
	 * Event list row.
	 */
	public function event_list_row( $event, $usecolumns = array() ) {
		if ( sp_column_active( $usecolumns, 'event_specs' ) ) {
			$event = new SP_Event( $event );
			$specs = $event->specs( false );
			$spec_labels = (array)sp_get_var_labels( 'sp_spec', null, false );

			foreach ( $spec_labels as $spec_label ) {
				?>
				<td class="data-spec">
				<?php if ( isset( $specs[$spec_label] ) ) {
						echo $specs[$spec_label]; 
					}else{
						echo '-';
					}?>
				</td>
				<?php
			}
		}
	}
	
	/**
	 * Add Event Specs after default template of Event blocks is loaded.
	 */
	public function event_blocks_after( $event, $usecolumns = array() ) {
		if ( sp_column_active( $usecolumns, 'event_specs' ) ) {
			$event = new SP_Event( $event );
			$specs = $event->specs( false );
			$spec_labels = (array)sp_get_var_labels( 'sp_spec', null, false );
			foreach ( $specs as $spec_label => $spec_value ) {
				echo '<div class="sp_event_spec"><span class="sp_event_spec_label">'.$spec_label.':</span><span class="sp_event_spec_value"> '.$spec_value.'</span></div>';
			}
		}
	}
}

endif;

new SportsPress_Event_Specs();
