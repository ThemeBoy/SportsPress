<?php
/**
 * SportsPress Meta Boxes
 *
 * Sets up the write panels used by custom post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Admin_Meta_Boxes
 */
class SP_Admin_Meta_Boxes {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
		add_action( 'add_meta_boxes', array( $this, 'rename_meta_boxes' ), 20 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );

		// Save Result Meta Boxes
		add_action( 'sportspress_process_sp_result_meta', 'SP_Meta_Box_Result_Details::save', 10, 2 );

		// Save Outcome Meta Boxes
		add_action( 'sportspress_process_sp_outcome_meta', 'SP_Meta_Box_Outcome_Details::save', 10, 2 );

		// Save Performance Meta Boxes
		add_action( 'sportspress_process_sp_performance_meta', 'SP_Meta_Box_Performance_Details::save', 10, 2 );

		// Save Column Meta Boxes
		add_action( 'sportspress_process_sp_column_meta', 'SP_Meta_Box_Column_Details::save', 10, 2 );

		// Save Event Meta Boxes
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Format::save', 10, 2 );
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Details::save', 20, 2 );
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Teams::save', 30, 2 );
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Video::save', 40, 2 );
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Results::save', 50, 2 );
		add_action( 'sportspress_process_sp_event_meta', 'SP_Meta_Box_Event_Performance::save', 60, 2 );

		// Save Calendar Meta Boxes
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Format::save', 10, 2 );
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Details::save', 20, 2 );
		add_action( 'sportspress_process_sp_calendar_meta', 'SP_Meta_Box_Calendar_Data::save', 30, 2 );

		// Save Team Meta Boxes
		add_action( 'sportspress_process_sp_team_meta', 'SP_Meta_Box_Team_Columns::save', 10, 2 );

		// Save Table Meta Boxes
		add_action( 'sportspress_process_sp_table_meta', 'SP_Meta_Box_Table_Details::save', 10, 2 );
		add_action( 'sportspress_process_sp_table_meta', 'SP_Meta_Box_Table_Data::save', 20, 2 );

		// Save Player Meta Boxes
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Details::save', 10, 2 );
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Metrics::save', 20, 2 );
		add_action( 'sportspress_process_sp_player_meta', 'SP_Meta_Box_Player_Performance::save', 30, 2 );

		// Save List Meta Boxes
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Format::save', 10, 2 );
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Details::save', 20, 2 );
		add_action( 'sportspress_process_sp_list_meta', 'SP_Meta_Box_List_Data::save', 30, 2 );
	}

	/**
	 * Add SP Meta boxes
	 */
	public function add_meta_boxes() {

		// Results
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Result_Details::output', 'sp_result', 'normal', 'high' );

		// Outcomes
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Outcome_Details::output', 'sp_outcome', 'normal', 'high' );

		// Performance
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Performance_Details::output', 'sp_performance', 'normal', 'high' );

		// Columns
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Column_Details::output', 'sp_column', 'normal', 'high' );

		// Events
		add_meta_box( 'sp_formatdiv', __( 'Format', 'sportspress' ), 'SP_Meta_Box_Event_Format::output', 'sp_event', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Event_Details::output', 'sp_event', 'side', 'default' );
		add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'SP_Meta_Box_Event_Teams::output', 'sp_event', 'side', 'default' );
		add_meta_box( 'sp_videodiv', __( 'Video', 'sportspress' ), 'SP_Meta_Box_Event_Video::output', 'sp_event', 'side', 'low' );
		add_meta_box( 'sp_resultsdiv', __( 'Results', 'sportspress' ), 'SP_Meta_Box_Event_Results::output', 'sp_event', 'normal', 'high' );
		add_meta_box( 'sp_performancediv', __( 'Performance', 'sportspress' ), 'SP_Meta_Box_Event_Performance::output', 'sp_event', 'normal', 'high' );
		add_meta_box( 'sp_articlediv', __( 'Article', 'sportspress' ), 'SP_Meta_Box_Event_Article::output', 'sp_event', 'normal', 'high' );

		// Calendars
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'SP_Meta_Box_Calendar_Shortcode::output', 'sp_calendar', 'side', 'default' );
		add_meta_box( 'sp_formatdiv', __( 'Layout', 'sportspress' ), 'SP_Meta_Box_Calendar_Format::output', 'sp_calendar', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Calendar_Details::output', 'sp_calendar', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Events', 'sportspress' ), 'SP_Meta_Box_Calendar_Data::output', 'sp_calendar', 'normal', 'high' );
		add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'SP_Meta_Box_Calendar_Description::output', 'sp_calendar', 'normal', 'high' );

		// Teams
		add_meta_box( 'sp_columnssdiv', __( 'Columns', 'sportspress' ), 'SP_Meta_Box_Team_Columns::output', 'sp_team', 'normal', 'high' );

		// Tables
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'SP_Meta_Box_Table_Shortcode::output', 'sp_table', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Table_Details::output', 'sp_table', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'League Table', 'sportspress' ), 'SP_Meta_Box_Table_Data::output', 'sp_table', 'normal', 'high' );
		add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'SP_Meta_Box_Table_Description::output', 'sp_table', 'normal', 'high' );

		// Players
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_Player_Details::output', 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_metricsdiv', __( 'Metrics', 'sportspress' ), 'SP_Meta_Box_Player_Metrics::output', 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_performancediv', __( 'Performance', 'sportspress' ), 'SP_Meta_Box_Player_Performance::output', 'sp_player', 'normal', 'high' );

		// Lists
		add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'SP_Meta_Box_List_Shortcode::output', 'sp_list', 'side', 'default' );
		add_meta_box( 'sp_formatdiv', __( 'Layout', 'sportspress' ), 'SP_Meta_Box_List_Format::output', 'sp_list', 'side', 'default' );
		add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'SP_Meta_Box_List_Details::output', 'sp_list', 'side', 'default' );
		add_meta_box( 'sp_datadiv', __( 'Player List', 'sportspress' ), 'SP_Meta_Box_List_Data::output', 'sp_list', 'normal', 'default' );
		add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'SP_Meta_Box_List_Description::output', 'sp_list', 'normal', 'high' );
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
		if ( ! current_user_can( 'edit_post', $post_id  )) return;
		if ( ! in_array( $post->post_type, array( 'sp_result', 'sp_outcome', 'sp_performance', 'sp_column', 'sp_metric', 'sp_statistic', 'sp_event', 'sp_calendar', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ) return;

		do_action( 'sportspress_process_' . $post->post_type . '_meta', $post_id, $post );
	}

}

new SP_Admin_Meta_Boxes();
