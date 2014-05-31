<?php
/**
 * Team Class
 *
 * The SportsPress team class handles individual team data.
 *
 * @class 		SP_Team
 * @version		0.8.4
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Team extends SP_Custom_Post {

	public function next_event() {
		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => 1,
			'posts_per_page' => 1,
			'order' => 'ASC',
			'post_status' => 'future',
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $this->ID
				)
			)
		);
		$events = get_posts( $args );

		if ( count( $events ) )
			return array_shift( $events );

		return false;
	}

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $league_id, $admin = false ) {
		$seasons = (array)get_the_terms( $this->ID, 'sp_season' );
		$columns = (array)get_post_meta( $this->ID, 'sp_columns', true );
		$leagues_seasons = sp_array_value( (array)get_post_meta( $this->ID, 'sp_leagues', true ), $league_id, array() );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Generate array of all season ids and season names
		$div_ids = array();
		$season_names = array();
		foreach ( $seasons as $season ):
			if ( is_object( $season ) && property_exists( $season, 'term_id' ) && property_exists( $season, 'name' ) ):
				$div_ids[] = $season->term_id;
				$season_names[ $season->term_id ] = $season->name;
			endif;
		endforeach;

		$data = array();

		// Get all seasons populated with data where available
		$data = sp_array_combine( $div_ids, sp_array_value( $columns, $league_id, array() ) );

		// Get equations from column variables
		$equations = sp_get_var_equations( 'sp_column' );

		// Initialize placeholders array
		$placeholders = array();

		foreach ( $div_ids as $div_id ):

			$totals = array( 'eventsplayed' => 0, 'streak' => 0, 'last5' => null, 'last10' => null );

			foreach ( $result_labels as $key => $value ):
				$totals[ $key . 'for' ] = 0;
				$totals[ $key . 'against' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $key ] = 0;
			endforeach;

			// Initialize streaks counter
			$streak = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize last counters
			$last5 = array();
			$last10 = array();

			// Add outcome types to last counters
			foreach( $outcome_labels as $key => $value ):
				$last5[ $key ] = 0;
				$last10[ $key ] = 0;
			endforeach;

			// Get all events involving the team in current season
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'order' => 'ASC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'sp_team',
						'value' => $this->ID
					),
					array(
						'key' => 'sp_format',
						'value' => 'league'
					)
				),
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'sp_league',
						'field' => 'id',
						'terms' => $league_id
					),
					array(
						'taxonomy' => 'sp_season',
						'field' => 'id',
						'terms' => $div_id
					),
				)
			);
			$events = get_posts( $args );

			foreach( $events as $event ):
				$results = (array)get_post_meta( $event->ID, 'sp_results', true );
				foreach ( $results as $team_id => $team_result ):
					foreach ( $team_result as $key => $value ):
						if ( $team_id == $this->ID ):
							if ( $key == 'outcome' ):

								// Convert to array
								if ( ! is_array( $value ) ):
									$value = array( $value );
								endif;

								foreach( $value as $outcome ):

									// Increment events played and outcome count
									if ( array_key_exists( $outcome, $totals ) ):
										$totals['eventsplayed']++;
										$totals[ $outcome ]++;
									endif;

									if ( $outcome && $outcome != '-1' ):

										// Add to streak counter
										if ( $streak['fire'] && ( $streak['name'] == '' || $streak['name'] == $outcome ) ):
											$streak['name'] = $outcome;
											$streak['count'] ++;
										else:
											$streak['fire'] = 0;
										endif;

										// Add to last 5 counter if sum is less than 5
										if ( array_key_exists( $outcome, $last5 ) && array_sum( $last5 ) < 5 ):
											$last5[ $outcome ] ++;
										endif;

										// Add to last 10 counter if sum is less than 10
										if ( array_key_exists( $outcome, $last10 ) && array_sum( $last10 ) < 10 ):
											$last10[ $outcome ] ++;
										endif;

									endif;

								endforeach;

							else:
								if ( array_key_exists( $key . 'for', $totals ) ):
									$totals[ $key . 'for' ] += $value;
								endif;
							endif;
						else:
							if ( $key != 'outcome' ):
								if ( array_key_exists( $key . 'against', $totals ) ):
									$totals[ $key . 'against' ] += $value;
								endif;
							endif;
						endif;
					endforeach;
				endforeach;
			endforeach;

			// Compile streaks counter and add to totals
			$args=array(
				'name' => $streak['name'],
				'post_type' => 'sp_outcome',
				'post_status' => 'publish',
				'posts_per_page' => 1
			);
			$outcomes = get_posts( $args );

			if ( $outcomes ):
				$outcome = reset( $outcomes );
				$totals['streak'] = $outcome->post_title . $streak['count'];
			endif;

			// Add last counters to totals
			$totals['last5'] = $last5;
			$totals['last10'] = $last10;

			// Generate array of placeholder values for each league
			$placeholders[ $div_id ] = array();
			foreach ( $equations as $key => $value ):
				$placeholders[ $div_id ][ $key ] = sp_solve( $value['equation'], $totals, $value['precision'] );
			endforeach;

		endforeach;

		// Get columns from column variables
		$columns = sp_get_var_labels( 'sp_column' );

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $season_id => $season_data ):

			if ( ! sp_array_value( $leagues_seasons, $season_id, 0 ) )
				continue;

			$season_name = sp_array_value( $season_names, $season_id, '&nbsp;' );

			// Add season name to row
			$merged[ $season_id ] = array(
				'name' => $season_name
			);

			foreach( $season_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $season_id, $data ) && array_key_exists( $key, $data[ $season_id ] ) && $data[ $season_id ][ $key ] != '' ):
					$merged[ $season_id ][ $key ] = $data[ $season_id ][ $key ];
				else:
					$merged[ $season_id ][ $key ] = $value;
				endif;

			endforeach;

		endforeach;

		if ( $admin ):
			return array( $columns, $data, $placeholders, $merged, $leagues_seasons );
		else:
			$labels = array_merge( array( 'name' => SP()->text->string('Season') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}
}
