<?php
/*
Plugin Name: SportsPress Team Assignments
Plugin URI: https://themeboy.com/
Description: Add team assignments support to SportsPress.
Author: Savvas
Author URI: https://themeboy.com/
Version: 2.8.0
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Team_Assignments' ) ) :
/**
 * Main SportsPress Team Assignments Class
 *
 * @class SportsPress_Team_Assignments
 * @version	2.8.0
 */
class SportsPress_Team_Assignments {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Actions

		// Filters
		add_filter( 'sportspress_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_filter( 'sportspress_league_table_args', array( $this, 'add_args' ) );
		add_filter( 'sportspress_league_table_teams', array( $this, 'add_teams' ), 10, 2 );
	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_VERSION' ) )
			define( 'SP_TEAM_ASSIGNMENTS_VERSION', '2.8.0' );
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_URL' ) )
			define( 'SP_TEAM_ASSIGNMENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_TEAM_ASSIGNMENTS_DIR' ) )
			define( 'SP_TEAM_ASSIGNMENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add meta boxes to team.
	 *
	 * @return array
	 */
	public function add_meta_boxes( $meta_boxes ) {
		$meta_boxes['sp_team']['assignments'] = array(
					'title' => __( 'Team Assignments', 'sportspress' ),
					'save' => array( $this, 'save' ),
					'output' => array( $this, 'output' ),
					'context' => 'normal',
					'priority' => 'default',
				);
		return $meta_boxes;
	}
	
	/**
	 * Output metabox for team assignments.
	 *
	 */
	 
	 public function output( $post ) {
		 
		// Get all leagues already assigned to the team
		$leagues = get_the_terms( $post, 'sp_league' );
		
		$league_ids = array();
		if ( $leagues ):
			foreach ( $leagues as $league ):
				$league_ids[] = $league->term_id;
			endforeach;
		else: return; //if no league assigned then exit
		endif;

		// Get all the seasons assigned to the team
		$seasons = get_the_terms( $post, 'sp_season' );
		
		$season_ids = array();
		if ( $seasons ):
			foreach ( $seasons as $season ):
				$season_ids[] = $season->term_id;
			endforeach;
		endif;
	
		$sp_team_assignments = get_post_meta( $post->ID, 'sp_assignments', true );
		?>
		<div class="sp-data-table-container">
			<table class="widefat sp-data-table sp-team-assignments">
				<thead>
					<tr>
						<th width="20%"><strong><?php _e( 'Leagues', 'sportspress' ); ?></strong></th>
						<th width="80%"><strong><?php _e( 'Seasons', 'sportspress' ); ?></strong></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $leagues as $league ) { ?>
					<tr>
						<td width="20%"><?php echo $league->name; ?></td>
						<td width="80%"><?php
						if ( $seasons ):
							$args = array(
								'taxonomy' => 'sp_season',
								'name' => 'sp_assignments[' . $league->term_id . '][]',
								'selected' => sp_array_value( $sp_team_assignments, $league->term_id, array() ),
								'include' => $season_ids,
								'values' => 'term_id',
								'placeholder' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
								'class' => 'widefat',
								'property' => 'multiple',
								'chosen' => true,
							);
							sp_dropdown_taxonomies( $args );
						else:
							_e( '&mdash; None &mdash;', 'sportspress' );
						endif;
						?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<?php
	 }
	 
	 /**
	 * Save metabox data for team assignments.
	 *
	 */
	 
	 public function save( $post_id ) {
		 
		//Reset current assignments
		delete_post_meta( $post_id, 'sp_assignments' );

		$sp_assignments = sp_array_value( $_POST, 'sp_assignments', array() );
		$sp_assignments_serialized = array();
		foreach ( $sp_assignments as $league_id => $season_ids ) {
			foreach ( $season_ids as $season_id ) {
				$sp_assignments_serialized[] = $league_id . '_' . $season_id . '_' . $post_id;
			}
		}
		if ( ! empty ( $sp_assignments ) ) {
			update_post_meta( $post_id, 'sp_assignments', $sp_assignments );
		}
		sp_update_post_meta_recursive( $post_id, 'sp_assignments_serialized', $sp_assignments_serialized );
		
	}
	
	/**
	 * Add args to filter out assigned teams
	 */
	 
	 public function add_args( $args = array() ) {

		$tax_query = (array) sp_array_value( $args, 'tax_query', array() );
		$league_ids = array();
		$season_ids = array();
		
		foreach ( $tax_query as $param ) {
			if ( 'sp_league' === sp_array_value( $param, 'taxonomy' ) ) $league_ids = sp_array_value( $param, 'terms', array() );
			if ( 'sp_season' === sp_array_value( $param, 'taxonomy' ) ) $season_ids = sp_array_value( $param, 'terms', array() );
		}

		if ( empty( $league_ids ) && empty( $season_ids ) ) return $args;
		
		$args['meta_query'][] = array(
			'key' => 'sp_assignments_serialized',
			'value' => '',
			'compare' => 'NOT EXISTS',
		);

		$args['meta_query']['relation'] = 'AND';

		return $args;
	 }
	 
	 /**
	 * Add assigned teams to league table
	 */
	public function add_teams( $teams = array(), $args = array() ) {

		$tax_query = (array) sp_array_value( $args, 'tax_query', array() );
		$league_ids = array();
		$season_ids = array();

		foreach ( $tax_query as $param ) {
			if ( 'sp_league' === sp_array_value( $param, 'taxonomy' ) ) $league_ids = sp_array_value( $param, 'terms', array() );
			if ( 'sp_season' === sp_array_value( $param, 'taxonomy' ) ) $season_ids = sp_array_value( $param, 'terms', array() );
		}

		if ( empty( $league_ids ) && empty( $season_ids ) ) return $teams;

		$assignments = array();
		
		if ( !empty( $league_ids ) && !empty( $season_ids ) ) {
			foreach ( $league_ids as $l_id ) {
				foreach ( $season_ids as $s_id ) {
					$assignments[] = $l_id.'_'.$s_id.'_';
					$compare = 'LIKE';
				}
			}
		}
		
		if ( empty( $league_ids ) && !empty( $season_ids ) ) {
			foreach ( $season_ids as $s_id ) {
				$assignments[] = '_'.$s_id.'_';
				$compare = 'LIKE';
			}
		}
		
		if ( !empty( $league_ids ) && empty( $season_ids ) ) {
			foreach ( $league_ids as $l_id ) {
					$assignments[] = $l_id.'_';
					$compare = 'LIKE';
			}
		}

		if ( sizeof( $assignments ) ) {
			$args['meta_query'] = array(
				'relation' => 'OR',
			);
			foreach( $assignments as $assignment ) {
				$args['meta_query'][] = array(
						'key'     => 'sp_assignments_serialized',
						'value'   => $assignment,
						'compare' => $compare,
						);
			}
		}

		$assigned_teams = (array) get_posts( $args );

		$teams = array_merge( $assigned_teams, $teams );

		return $teams;
	}
}
endif;

new SportsPress_Team_Assignments();
