<?php
/**
 * Admin functions for the events post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version     1.8.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Event' ) ) :

/**
 * SP_Admin_CPT_Event Class
 */
class SP_Admin_CPT_Event extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_event';

		// Post title fields
		add_filter( 'enter_title_here', array( $this, 'enter_title_here' ), 1, 2 );

		// Before data updates
		add_filter( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 99, 2 );

		// Admin Columns
		add_filter( 'manage_edit-sp_event_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_event_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_filter( 'parse_query', array( $this, 'filters_query' ) );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change title boxes in admin.
	 * @param  string $text
	 * @param  object $post
	 * @return string
	 */
	public function enter_title_here( $text, $post ) {
		if ( $post->post_type == 'sp_event' )
			return __( '(Auto)', 'sportspress' );

		return $text;
	}

	/**
	 * Auto-generate an event title based on the team playing if left blank.
	 *
	 * @param array $data
	 * @return array
	 */
	public function wp_insert_post_data( $data, $postarr ) {
		if ( $data['post_type'] == 'sp_event' && $data['post_title'] == '' ):

			$teams = sp_array_value( $postarr, 'sp_team', array() );
			$teams = array_filter( $teams );

			$team_names = array();
			foreach ( $teams as $team ):
				while ( is_array( $team ) ) {
					$team = array_shift( array_filter( $team ) );
				}
				if ( $team > 0 ) $team_names[] = get_the_title( $team );
			endforeach;

			$team_names = array_unique( $team_names );

			$data['post_title'] = implode( ' ' . get_option( 'sportspress_event_teams_delimiter', 'vs' ) . ' ', $team_names );

		endif;

		return $data;
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		unset( $existing_columns['author'], $existing_columns['comments'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'sp_format' => '<span class="dashicons sp-icon-calendar sp-tip" title="' . __( 'Format', 'sportspress' ) . '"></span>',
			'title' => null,
			'date' => __( 'Date', 'sportspress' ),
			'sp_time' => __( 'Time', 'sportspress' ),
			'sp_team' => __( 'Teams', 'sportspress' ),
			'sp_league' => __( 'Competition', 'sportspress' ),
			'sp_season' => __( 'Season', 'sportspress' ),
			'sp_venue' => __( 'Venue', 'sportspress' ),
		), $existing_columns, array(
			'title' => __( 'Event', 'sportspress' ),
		) );
		return apply_filters( 'sportspress_event_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_format':
				$format = get_post_meta( $post_id, 'sp_format', true );
				$formats = new SP_Formats();
				$event_formats = $formats->event;
				if ( array_key_exists( $format, $event_formats ) ):
					echo '<span class="dashicons sp-icon-' . $format . ' sp-tip" title="' . $event_formats[ $format ] . '"></span>';
				endif;
				break;
			case 'sp_time':
				echo get_post_time( 'H:i', false, $post_id, true );
				break;
			case 'sp_team':
				$teams = (array)get_post_meta( $post_id, 'sp_team', false );
				$teams = array_filter( $teams );
				$teams = array_unique( $teams );
				if ( empty( $teams ) ):
					echo '&mdash;';
				else:
					$results = get_post_meta( $post_id, 'sp_results', true );
					$main_result = get_option( 'sportspress_primary_result', null );
					echo '<input type="hidden" name="sp_post_id" value="' . $post_id . '">';
					echo '<div class="sp-results">';
						foreach( $teams as $team_id ):
							if ( ! $team_id ) continue;
							$team = get_post( $team_id );

							if ( $team ):
								$team_results = sportspress_array_value( $results, $team_id, null );

								if ( $main_result ):
									$team_result = sportspress_array_value( $team_results, $main_result, null );
								else:
									if ( is_array( $team_results ) ):
										end( $team_results );
										$team_result = prev( $team_results );
										$main_result = key( $team_results );
									else:
										$team_result = null;
									endif;
								endif;

								if ( is_array( $team_results ) ):
									unset( $team_results['outcome'] );
									$team_results = array_filter( $team_results, 'sp_filter_non_empty' );
									$team_results = implode( ' | ', $team_results );
								endif;

								echo '<a class="sp-result sp-tip" tabindex="10" title="' . $team_results . '" data-team="' . $team_id . '" href="#">' . ( $team_result == '' ? '-' : apply_filters( 'sportspress_event_team_result_admin', $team_result, $post_id, $team_id ) ) . '</a>';
								echo '<input type="text" tabindex="10" class="sp-edit-result hidden small-text" data-team="' . $team_id . '" data-key="' . $main_result . '" value="' . $team_result . '"> ';
								echo $team->post_title;
								echo '<br>';
							endif;
						endforeach;
					echo '</div>';
					if ( current_user_can( 'edit_others_sp_events' ) ) {
						?>
						<div class="row-actions sp-row-actions"><span class="inline hide-if-no-js"><a href="#" class="sp-edit-results"><?php _e( 'Edit Results', 'sportspress' ); ?></a></span></div>
						<p class="inline-edit-save sp-inline-edit-save hidden">
							<a href="#inline-edit" class="button-secondary cancel alignleft"><?php _e( 'Cancel' ); ?></a>
							<a href="#inline-edit" class="button-primary save alignright"><?php _e( 'Update' ); ?></a>
						</p>
						<?php
					}
				endif;
				break;
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : '&mdash;';
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : '&mdash;';
				break;
			case 'sp_venue':
				echo get_the_terms ( $post_id, 'sp_venue' ) ? the_terms( $post_id, 'sp_venue' ) : '&mdash;';
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_event' )
	    	return;

		$selected = isset( $_REQUEST['team'] ) ? $_REQUEST['team'] : null;
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'team',
			'show_option_none' => __( 'Show all teams', 'sportspress' ),
			'selected' => $selected,
			'values' => 'ID',
		);
		wp_dropdown_pages( $args );

		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all competitions', 'sportspress' ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );

		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all seasons', 'sportspress' ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );

		if ( current_user_can( 'edit_others_sp_events' ) )
			wp_nonce_field( 'sp-save-inline-results', 'sp-inline-nonce', false );
	}

	/**
	 * Filter in admin based on options
	 *
	 * @param mixed $query
	 */
	public function filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( $typenow == 'sp_event' ) {

	    	if ( ! empty( $_GET['team'] ) ) {
		    	$query->query_vars['meta_value'] 	= $_GET['team'];
		        $query->query_vars['meta_key'] 		= 'sp_team';
		    }
		}
	}
}

endif;

return new SP_Admin_CPT_Event();
