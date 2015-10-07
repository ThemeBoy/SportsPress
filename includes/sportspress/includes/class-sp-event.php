<?php
/**
 * Event Class
 *
 * The SportsPress event class handles individual event data.
 *
 * @class 		SP_Event
 * @version		1.9
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Event extends SP_Custom_Post{
	
	public function status() {
		$post_status = $this->post->post_status;
		$results = get_post_meta( $this->ID, 'sp_results', true );
		if ( is_array( $results ) ) {
			foreach( $results as $result ) {
				$result = array_filter( $result );
				if ( count( $result ) > 0 ) {
					return 'results';
				}
			}
		}
		return $post_status;
	}

	public function results( $admin = false ) {
		$teams = (array)get_post_meta( $this->ID, 'sp_team', false );
		$results = (array)get_post_meta( $this->ID, 'sp_results', true );

		// Get columns from result variables
		$columns = sp_get_var_labels( 'sp_result' );

		// Get result columns to display
		$usecolumns = get_post_meta( $this->ID, 'sp_result_columns', true );

		// Get results for all teams
		$data = sp_array_combine( $teams, $results, true );

		if ( $admin ):
			return array( $columns, $usecolumns, $data );
		else:
			// Add outcome to result columns
			$columns['outcome'] = __( 'Outcome', 'sportspress' );
			if ( is_array( $usecolumns ) ):
				if ( 'manual' == get_option( 'sportspress_event_result_columns', 'auto' ) ):
					foreach ( $columns as $key => $label ):
						if ( ! in_array( $key, $usecolumns ) ):
							unset( $columns[ $key ] );
						endif;
					endforeach;
				else:
					$active_columns = array();
					foreach ( $data as $team_results ):
						foreach ( $team_results as $key => $result ):
							if ( is_string( $result ) && strlen( $result ) ):
								$active_columns[ $key ] = $key;
							endif;
						endforeach;
					endforeach;
					$columns = array_intersect_key( $columns, $active_columns );
				endif;

				if ( 'yes' == get_option( 'sportspress_event_show_outcome', 'no' ) ):
					$columns['outcome'] = __( 'Outcome', 'sportspress' );
				endif;
			endif;
			$data[0] = $columns;
			return $data;
		endif;
	}

	public function performance( $admin = false ) {
		$teams = get_post_meta( $this->ID, 'sp_team', false );
		$performance = (array)get_post_meta( $this->ID, 'sp_players', true );
		$labels = apply_filters( 'sportspress_event_performance_labels', sp_get_var_labels( 'sp_performance' ), $this );
		$columns = get_post_meta( $this->ID, 'sp_columns', true );
		if ( is_array( $teams ) ):
			foreach( $teams as $i => $team_id ):
				$players = sp_array_between( (array)get_post_meta( $this->ID, 'sp_player', false ), 0, $i );
				$data = sp_array_combine( $players, sp_array_value( $performance, $team_id, array() ) );

				$totals = array();
				foreach( $labels as $key => $label ):
					$totals[ $key ] = 0;
				endforeach;

				foreach( $data as $player_id => $player_performance ):
					foreach( $labels as $key => $label ):
						if ( array_key_exists( $key, $totals ) ):
							$totals[ $key ] += sp_array_value( $player_performance, $key, 0 );
						endif;
					endforeach;
					if ( ! array_key_exists( 'number', $player_performance ) ):
						$performance[ $team_id ][ $player_id ]['number'] = get_post_meta( $player_id, 'sp_number', true );
					endif;
					if ( ! array_key_exists( 'position', $player_performance ) || $player_performance['position'] == null ):
						$performance[ $team_id ][ $player_id ]['position'] = sp_get_the_term_id( $player_id, 'sp_position', null );
					endif;
				endforeach;

				foreach( $totals as $key => $value ):
					$manual_total = sp_array_value( sp_array_value( $performance, 0, array() ), $key, null );
					if ( $manual_total != null ):
						$totals[ $key ] = $manual_total;
					endif;
				endforeach;
			endforeach;
		endif;

		if ( $admin ):
			return array( $labels, $columns, $performance, $teams );
		else:
			// Add position to performance labels
			if ( taxonomy_exists( 'sp_position' ) ):
				$labels = array_merge( array( 'position' => __( 'Position', 'sportspress' )  ), $labels );
			endif;
			if ( 'manual' == get_option( 'sportspress_event_performance_columns', 'auto' ) && is_array( $columns ) ):
				foreach ( $labels as $key => $label ):
					if ( ! in_array( $key, $columns ) ):
						unset( $labels[ $key ] );
					endif;
				endforeach;
			endif;

			if ( 'no' == get_option( 'sportspress_event_show_position', 'yes' ) ):
				unset( $labels['position'] );
			endif;
			$performance[0] = $labels;
			return apply_filters( 'sportspress_get_event_performance', $performance );
		endif;
	}

	public function main_results() {
		// Get main result option
		$main_result = get_option( 'sportspress_primary_result', null );

		// Get teams from event
		$teams = get_post_meta( $this->ID, 'sp_team', false );
		
		// Initialize output
		$output = array();

		// Return empty array if there are no teams
		if ( ! $teams ) return $output;

		// Get results from event
		$results = get_post_meta( $this->ID, 'sp_results', true );

		// Loop through teams			
		foreach ( $teams as $team_id ) {

			// Skip if not a team
			if ( ! $team_id ) continue;

			// Get team results from all results
			$team_results = sp_array_value( $results, $team_id, null );

			// Get main or last result
			if ( $main_result ) {
			
				// Get main result from team results
				$team_result = sp_array_value( $team_results, $main_result, null );
			} else {

				// If there are any team results available
				if ( is_array( $team_results ) ) {

					// Get last result that is not outcome
					unset( $team_results['outcome'] );
					$team_result = end( $team_results );
				} else {

					// Give team null result
					$team_result = null;
				}
			}

			if ( null != $team_result ) {
				$output[] = $team_result;
			}
		}

		return $output;
	}

	public function outcome( $single = true ) {
		// Get teams from event
		$teams = get_post_meta( $this->ID, 'sp_team', false );
		
		// Initialize output
		$output = array();

		// Return empty array if there are no teams
		if ( ! $teams ) return $output;

		// Get results from event
		$results = get_post_meta( $this->ID, 'sp_results', true );

		// Loop through teams			
		foreach ( $teams as $team_id ) {

			// Skip if not a team
			if ( ! $team_id ) continue;

			// Get team results from all results
			$team_results = sp_array_value( $results, $team_id, null );

			// Get outcome from team results
			$team_outcome = sp_array_value( $team_results, 'outcome', null );

			if ( null != $team_outcome ) {

				// Make sure that we have an array of outcomes
				$team_outcome = (array) $team_outcome;

				// Use only first outcome if single
				if ( $single ) {
					$team_outcome = reset( $team_outcome );
				}

				// Add outcome to output
				$output[ $team_id ] = $team_outcome;
			}
		}

		return $output;
	}

	public function winner() {
		// Get the first configured outcome
		$outcome = get_posts( array(
			'post_type' => 'sp_outcome',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		) );

		// Return if no outcomes available
		if ( ! $outcome ) return null;

		$outcome = reset( $outcome );

		// Get event outcomes
		$outcomes = self::outcome( false );

		// Look for a team that meets the criteria
		foreach ( $outcomes as $team_id => $team_outcomes ) {
			if ( in_array( $outcome->post_name, $team_outcomes ) ) {
				return $team_id;
			}
		}

		// Return if no teams meet criteria
		return null;
	}

	public function update_main_results( $results ) {
		$main_result = sp_get_main_result_option();

		if ( ! $this->ID || ! is_array( $results ) || null === $main_result ) {
			return false;
		}

		// Get current results meta
		$meta = get_post_meta( $this->ID, 'sp_results', true );

		$primary_results = array();
		foreach ( $results as $id => $result ) {
			$primary_results[ $id ] = $result;

			if ( ! $id ) continue;

			$meta[ $id ][ $main_result ] = $result;
		}

		arsort( $primary_results );

		if ( count( $primary_results ) && ! in_array( null, $primary_results ) ) {
			if ( count( array_unique( $primary_results ) ) === 1 ) {
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '=',
				);
				$outcomes = get_posts( $args );
				foreach ( $meta as $team => $team_results ) {
					if ( $outcomes ) {
						$meta[ $team ][ 'outcome' ] = array();
						foreach ( $outcomes as $outcome ) {
							$meta[ $team ][ 'outcome' ][] = $outcome->post_name;
						}
					}
				}
			} else {
				// Get default outcomes
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => 'else',
				);
				$default_outcomes = get_posts( $args );

				// Get greater than outcomes
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '>',
				);
				$gt_outcomes = get_posts( $args );
				if ( empty ( $gt_outcomes ) ) $gt_outcomes = $default_outcomes;

				// Get less than outcomes
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '<',
				);
				$lt_outcomes = get_posts( $args );
				if ( empty ( $lt_outcomes ) ) $lt_outcomes = $default_outcomes;

				// Get min and max values
				$min = min( $primary_results );
				$max = max( $primary_results );

				foreach ( $primary_results as $key => $value ) {
					if ( $min == $value ) {
						$outcomes = $lt_outcomes;
					} elseif ( $max == $value ) {
						$outcomes = $gt_outcomes;
					} else {
						$outcomes = $default_outcomes;
					}
					$meta[ $key ][ 'outcome' ] = array();
					foreach ( $outcomes as $outcome ) {
						$meta[ $key ][ 'outcome' ][] = $outcome->post_name;
					}
				}
			}
		}

		// Update results
		update_post_meta( $this->ID, 'sp_results', $meta );
	}

	public function lineup_filter( $v ) {
		return sp_array_value( $v, 'status', 'lineup' ) == 'lineup';
	}

	public function sub_filter( $v ) {
		return sp_array_value( $v, 'status', 'lineup' ) == 'sub';
	}
}
