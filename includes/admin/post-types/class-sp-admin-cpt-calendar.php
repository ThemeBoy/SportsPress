<?php
/**
 * Admin functions for the calendars post type
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version		2.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) )
	include( 'class-sp-admin-cpt.php' );

if ( ! class_exists( 'SP_Admin_CPT_Calendar' ) ) :

/**
 * SP_Admin_CPT_Calendar Class
 */
class SP_Admin_CPT_Calendar extends SP_Admin_CPT {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->type = 'sp_calendar';

		// Admin Columns
		add_filter( 'manage_edit-sp_calendar_columns', array( $this, 'edit_columns' ) );
		add_action( 'manage_sp_calendar_posts_custom_column', array( $this, 'custom_columns' ), 2, 2 );

		// Filtering
		add_action( 'restrict_manage_posts', array( $this, 'filters' ) );
		add_filter( 'parse_query', array( $this, 'filters_query' ) );
		
		// Call SP_Admin_CPT constructor
		parent::__construct();
	}

	/**
	 * Change the columns shown in admin.
	 */
	public function edit_columns( $existing_columns ) {
		unset( $existing_columns['author'], $existing_columns['date'] );
		$columns = array_merge( array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Title', 'sportspress' ),
			'sp_league' => __( 'League', 'sportspress' ),
			'sp_season' => __( 'Season', 'sportspress' ),
			'sp_venue' => __( 'Venue', 'sportspress' ),
			'sp_team' => __( 'Team', 'sportspress' ),
			'sp_events' => __( 'Events', 'sportspress' ),
			'sp_layout' => __( 'Layout', 'sportspress' ),
		), $existing_columns );
		return apply_filters( 'sportspress_calendar_admin_columns', $columns );
	}

	/**
	 * Define our custom columns shown in admin.
	 * @param  string $column
	 */
	public function custom_columns( $column, $post_id ) {
		switch ( $column ):
			case 'sp_league':
				echo get_the_terms ( $post_id, 'sp_league' ) ? the_terms( $post_id, 'sp_league' ) : __( 'All', 'sportspress' );
				break;
			case 'sp_season':
				echo get_the_terms ( $post_id, 'sp_season' ) ? the_terms( $post_id, 'sp_season' ) : __( 'All', 'sportspress' );
				break;
			case 'sp_venue':
				echo get_the_terms ( $post_id, 'sp_venue' ) ? the_terms( $post_id, 'sp_venue' ) : __( 'All', 'sportspress' );
				break;
			case 'sp_team':
				$teams = (array)get_post_meta( $post_id, 'sp_team', false );
				$teams = array_filter( $teams );
				if ( empty( $teams ) ):
					echo __( 'All', 'sportspress' );
				else:
					$current_team = get_post_meta( $post_id, 'sp_current_team', true );
					foreach( $teams as $team_id ):
						if ( ! $team_id ) continue;
						$team = get_post( $team_id );
						if ( $team ):
							echo $team->post_title;
							if ( $team_id == $current_team ):
								echo '<span class="dashicons dashicons-yes" title="' . __( 'Current Team', 'sportspress' ) . '"></span>';
							endif;
							echo '<br>';
						endif;
					endforeach;
				endif;
				break;
			case 'sp_events':
				$calendar = new SP_Calendar( $post_id );
				echo sizeof( $calendar->data() );
				break;
			case 'sp_layout':
				echo sp_array_value( SP()->formats->calendar, get_post_meta( $post_id, 'sp_format', true ), '&mdash;' );
				break;
		endswitch;
	}

	/**
	 * Show a category filter box
	 */
	public function filters() {
		global $typenow, $wp_query;

	    if ( $typenow != 'sp_calendar' )
	    	return;

		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  __( 'Show all leagues', 'sportspress' ),
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

		$selected = isset( $_REQUEST['team'] ) ? $_REQUEST['team'] : null;
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'team',
			'show_option_none' => __( 'Show all teams', 'sportspress' ),
			'selected' => $selected,
			'values' => 'ID',
		);
		wp_dropdown_pages( $args );
	}

	/**
	 * Filter in admin based on options
	 *
	 * @param mixed $query
	 */
	public function filters_query( $query ) {
		global $typenow, $wp_query;

	    if ( $typenow == 'sp_calendar' ) {

	    	if ( ! empty( $_GET['team'] ) ) {
		    	$query->query_vars['meta_value'] 	= $_GET['team'];
		        $query->query_vars['meta_key'] 		= 'sp_team';
		    }
		}
	}
}

endif;

return new SP_Admin_CPT_Calendar();
