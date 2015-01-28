<?php
/**
 * SportsPress Meta Boxes
 *
 * Sets up the write panels used by custom post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Admin_Meta_Boxes
 */
class SP_Admin_Meta_Boxes {

	/**
	 * @var array
	 */
	public $meta_boxes = null;

	/**
	 * Constructor
	 */
	public function __construct() {
		$meta_boxes = array(
			'sp_result' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Result_Details::save',
					'output' => 'SP_Meta_Box_Result_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_outcome' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Outcome_Details::save',
					'output' => 'SP_Meta_Box_Outcome_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_metric' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Metric_Details::save',
					'output' => 'SP_Meta_Box_Metric_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_outcome' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Metric_Details::save',
					'output' => 'SP_Meta_Box_Metric_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_performance' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Performance_Details::save',
					'output' => 'SP_Meta_Box_Performance_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_statistic' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Statistic_Details::save',
					'output' => 'SP_Meta_Box_Statistic_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'equation' => array(
					'title' => __( 'Equation', 'sportspress' ),
					'save' => 'SP_Meta_Box_Statistic_Equation::save',
					'output' => 'SP_Meta_Box_Statistic_Equation::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_column' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Column_Details::save',
					'output' => 'SP_Meta_Box_Column_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'equation' => array(
					'title' => __( 'Equation', 'sportspress' ),
					'save' => 'SP_Meta_Box_Column_Equation::save',
					'output' => 'SP_Meta_Box_Column_Equation::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_event' => array(
				'shortcode' => array(
					'title' => __( 'Shortcodes', 'sportspress' ),
					'output' => 'SP_Meta_Box_Event_Shortcode::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'format' => array(
					'title' => __( 'Format', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Format::save',
					'output' => 'SP_Meta_Box_Event_Format::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Details::save',
					'output' => 'SP_Meta_Box_Event_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'team' => array(
					'title' => __( 'Teams', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Teams::save',
					'output' => 'SP_Meta_Box_Event_Teams::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'results' => array(
					'title' => __( 'Event Results', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Results::save',
					'output' => 'SP_Meta_Box_Event_Results::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'performance' => array(
					'title' => __( 'Player Performance', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Performance::save',
					'output' => 'SP_Meta_Box_Event_Performance::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'editor' => array(
					'title' => __( 'Article', 'sportspress' ),
					'output' => 'SP_Meta_Box_Event_Editor::output',
					'context' => 'normal',
					'priority' => 'low',
				),
			),
		);

		$this->meta_boxes = apply_filters( 'sportspress_meta_boxes', $meta_boxes );

		foreach ( $this->meta_boxes as $post_type => $meta_boxes ) {
			$i = 0;
			foreach ( $meta_boxes as $id => $meta_box ) {
				if ( array_key_exists( 'save', $meta_box ) ) {
					add_action( 'sportspress_process_' . $post_type . '_meta', $meta_box['save'], ( $i + 1 ) * 10, 2 );
				}
				$i++;
			}
		}

		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'rename_meta_boxes' ), 20 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

		// Save Calendar Meta Boxes
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Format::save', 10, 2 );
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Details::save', 20, 2 );
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Data::save', 30, 2 );

		// Save Team Meta Boxes
		add_action( 'sportspress_process_sp_team_meta', 'SP_Meta_Box_Team_Details::save', 10, 2 );

		// Save Table Meta Boxes
		add_action( 'sportspress_process_sp_table_meta', 'SP_Meta_Box_Table_Details::save', 10, 2 );
		add_action( 'sportspress_process_sp_table_meta', 'SP_Meta_Box_Table_Data::save', 20, 2 );

		// Save Player Meta Boxes
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Columns::save', 10, 2 );
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Details::save', 20, 2 );
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Metrics::save', 30, 2 );
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Statistics::save', 40, 2 );

		// Save List Meta Boxes
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Format::save', 10, 2 );
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Columns::save', 20, 2 );
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Details::save', 30, 2 );
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Data::save', 40, 2 );

		// Save Staff Meta Boxes
		add_action( 'sportspress_process_sp_staff_meta', 'SP_Meta_Box_Staff_Details::save', 10, 2 );
	}

	/**
	 * Add SP Meta boxes
	 */
	public function add_meta_boxes() {
		global $post;

		foreach ( $this->meta_boxes as $post_type => $meta_boxes ) {
			foreach ( $meta_boxes as $id => $meta_box ) {
				if ( array_key_exists( 'output', $meta_box ) ) {
					add_meta_box( 'sp_' . $id . 'div', $meta_box['title'], $meta_box['output'], $post_type, $meta_box['context'], $meta_box['priority'] );
				}
			}
		}

		// Get post meta array
		if ( isset( $post ) && isset( $post->ID ) )
			$post_meta = get_post_meta( $post->ID );
		else
			$post_meta = array();

		// Teams
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Team_Details::output', 'sp_team', 'side', 'default' );
		add_meta_box( 'sp_editordiv', __( 'Profile', 'sportspress' ), 'SP_Meta_Box_Team_Editor::output', 'sp_team', 'normal', 'low' );

		// Players
		add_meta_box( 'sp_shortcodediv', __( 'Shortcodes', 'sportspress' ), 'SP_Meta_Box_Player_Shortcode::output', 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_columnsdiv', __( 'Columns', 'sportspress' ), 'SP_Meta_Box_Player_Columns::output', 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Player_Details::output', 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_metricsdiv', __( 'Metrics', 'sportspress' ), 'SP_Meta_Box_Player_Metrics::output', 'sp_player', 'side', 'default' );
		if ( isset( $post ) && isset( $post->ID ) ):
			add_meta_box( 'sp_statisticsdiv', __( 'Statistics', 'sportspress' ), 'SP_Meta_Box_Player_Statistics::output', 'sp_player', 'normal', 'high' );
		endif;
		add_meta_box( 'sp_editordiv', __( 'Profile', 'sportspress' ), 'SP_Meta_Box_Player_Editor::output', 'sp_player', 'normal', 'low' );

		// Staff
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'SP_Meta_Box_Staff_Shortcode::output', 'sp_staff', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Staff_Details::output', 'sp_staff', 'side', 'default' );
		add_meta_box( 'sp_editordiv', __( 'Profile', 'sportspress' ), 'SP_Meta_Box_Staff_Editor::output', 'sp_staff', 'normal', 'low' );
	}

	/**
	 * Remove bloat
	 */
	public function remove_meta_boxes() {

		// Events
		remove_meta_box( 'sp_venuediv', 'sp_event', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_event', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_event', 'side' );

		// Calendars
		remove_meta_box( 'sp_seasondiv', 'sp_calendar', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_calendar', 'side' );
		remove_meta_box( 'sp_venuediv', 'sp_calendar', 'side' );

		// Teams
		remove_meta_box( 'sp_leaguediv', 'sp_team', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_team', 'side' );
		remove_meta_box( 'sp_venuediv', 'sp_team', 'side' );

		// Tables
		remove_meta_box( 'sp_seasondiv', 'sp_table', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_table', 'side' );

		// Players
		remove_meta_box( 'sp_seasondiv', 'sp_player', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_player', 'side' );
		remove_meta_box( 'sp_positiondiv', 'sp_player', 'side' );

		// Lists
		remove_meta_box( 'sp_seasondiv', 'sp_list', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_list', 'side' );

		// Staff
		remove_meta_box( 'sp_rolediv', 'sp_staff', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_staff', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_staff', 'side' );
	}

	/**
	 * Rename core meta boxes
	 */
	public function rename_meta_boxes() {
		global $post;

		// Publish/Event
		if ( isset( $post ) ) {
			remove_meta_box( 'submitdiv', 'sp_event', 'side' );

			add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_event', 'side', 'high' );
		}
	}

	/**
	 * Check if we're saving, then trigger an action based on the post type
	 *
	 * @param  int $post_id
	 * @param  object $post
	 */
	public function save_meta_boxes( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) ) return;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( empty( $_POST['sportspress_meta_nonce'] ) || ! wp_verify_nonce( $_POST['sportspress_meta_nonce'], 'sportspress_save_data' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id  ) ) return;
		if ( ! is_sp_post_type( $post->post_type ) && ! is_sp_config_type( $post->post_type ) ) return;

		do_action( 'sportspress_process_' . $post->post_type . '_meta', $post_id, $post );
	}

}

new SP_Admin_Meta_Boxes();
