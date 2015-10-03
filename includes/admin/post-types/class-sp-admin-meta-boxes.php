<?php
/**
 * SportsPress Meta Boxes
 *
 * Sets up the write panels used by custom post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.6
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
			'sp_outcome' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Outcome_Details::save',
					'output' => 'SP_Meta_Box_Outcome_Details::output',
					'context' => 'normal',
					'priority' => 'high',
				),
			),
			'sp_result' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Result_Details::save',
					'output' => 'SP_Meta_Box_Result_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'equation' => array(
					'title' => __( 'Equation', 'sportspress' ),
					'save' => 'SP_Meta_Box_Result_Equation::save',
					'output' => 'SP_Meta_Box_Result_Equation::output',
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
			'sp_metric' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Metric_Details::save',
					'output' => 'SP_Meta_Box_Metric_Details::output',
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
					'title' => __( 'Results', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Results::save',
					'output' => 'SP_Meta_Box_Event_Results::output',
					'context' => 'normal',
					'priority' => 'high',
				),
				'performance' => array(
					'title' => __( 'Scorecard', 'sportspress' ),
					'save' => 'SP_Meta_Box_Event_Performance::save',
					'output' => 'SP_Meta_Box_Event_Performance::output',
					'context' => 'normal',
					'priority' => 'high',
				),
				'editor' => array(
					'title' => __( 'Article', 'sportspress' ),
					'output' => 'SP_Meta_Box_Event_Editor::output',
					'context' => 'normal',
					'priority' => 'low',
				),
			),
			'sp_team' => array(
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Team_Details::save',
					'output' => 'SP_Meta_Box_Team_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'editor' => array(
					'title' => __( 'Profile', 'sportspress' ),
					'output' => 'SP_Meta_Box_Team_Editor::output',
					'context' => 'normal',
					'priority' => 'low',
				),
			),
			'sp_player' => array(
				'shortcode' => array(
					'title' => __( 'Shortcodes', 'sportspress' ),
					'output' => 'SP_Meta_Box_Player_Shortcode::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'columns' => array(
					'title' => __( 'Columns', 'sportspress' ),
					'save' => 'SP_Meta_Box_Player_Columns::save',
					'output' => 'SP_Meta_Box_Player_Columns::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Player_Details::save',
					'output' => 'SP_Meta_Box_Player_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'metrics' => array(
					'title' => __( 'Metrics', 'sportspress' ),
					'save' => 'SP_Meta_Box_Player_Metrics::save',
					'output' => 'SP_Meta_Box_Player_Metrics::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'statistics' => array(
					'title' => __( 'Statistics', 'sportspress' ),
					'save' => 'SP_Meta_Box_Player_Statistics::save',
					'output' => 'SP_Meta_Box_Player_Statistics::output',
					'context' => 'normal',
					'priority' => 'high',
				),
				'editor' => array(
					'title' => __( 'Profile', 'sportspress' ),
					'output' => 'SP_Meta_Box_Player_Editor::output',
					'context' => 'normal',
					'priority' => 'low',
				),
			),
			'sp_staff' => array(
				'shortcode' => array(
					'title' => __( 'Shortcode', 'sportspress' ),
					'output' => 'SP_Meta_Box_Staff_Shortcode::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'details' => array(
					'title' => __( 'Details', 'sportspress' ),
					'save' => 'SP_Meta_Box_Staff_Details::save',
					'output' => 'SP_Meta_Box_Staff_Details::output',
					'context' => 'side',
					'priority' => 'default',
				),
				'editor' => array(
					'title' => __( 'Profile', 'sportspress' ),
					'output' => 'SP_Meta_Box_Staff_Editor::output',
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
	}

	/**
	 * Add SP Meta boxes
	 */
	public function add_meta_boxes() {
		foreach ( $this->meta_boxes as $post_type => $meta_boxes ) {
			foreach ( $meta_boxes as $id => $meta_box ) {
				if ( array_key_exists( 'output', $meta_box ) ) {
					add_meta_box( 'sp_' . $id . 'div', $meta_box['title'], $meta_box['output'], $post_type, $meta_box['context'], $meta_box['priority'] );
				}
			}
		}
	}

	/**
	 * Remove bloat
	 */
	public function remove_meta_boxes() {

		// Events
		remove_meta_box( 'sp_venuediv', 'sp_event', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_event', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_event', 'side' );

		// Teams
		remove_meta_box( 'sp_leaguediv', 'sp_team', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_team', 'side' );
		remove_meta_box( 'sp_venuediv', 'sp_team', 'side' );

		// Players
		remove_meta_box( 'sp_seasondiv', 'sp_player', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_player', 'side' );
		remove_meta_box( 'sp_positiondiv', 'sp_player', 'side' );

		// Staff
		remove_meta_box( 'sp_rolediv', 'sp_staff', 'side' );
		remove_meta_box( 'sp_seasondiv', 'sp_staff', 'side' );
		remove_meta_box( 'sp_leaguediv', 'sp_staff', 'side' );
	}

	/**
	 * Rename core meta boxes
	 */
	public function rename_meta_boxes() {
		remove_meta_box( 'submitdiv', 'sp_event', 'side' );
		add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_event', 'side', 'high' );

		remove_meta_box( 'postimagediv', 'sp_team', 'side' );
		add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'low' );

		remove_meta_box( 'postimagediv', 'sp_player', 'side' );
		add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'low' );

		remove_meta_box( 'postimagediv', 'sp_staff', 'side' );
		add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_staff', 'side', 'low' );

		remove_meta_box( 'postimagediv', 'sp_performance', 'side' );
		add_meta_box( 'postimagediv', __( 'Icon', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_performance', 'side', 'low' );
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
		if ( ! apply_filters( 'sportspress_user_can', current_user_can( 'edit_post', $post_id  ), $post_id ) ) return;
		if ( ! is_sp_post_type( $post->post_type ) && ! is_sp_config_type( $post->post_type ) ) return;

		do_action( 'sportspress_process_' . $post->post_type . '_meta', $post_id, $post );
	}

}

new SP_Admin_Meta_Boxes();
