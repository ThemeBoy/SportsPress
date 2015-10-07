<?php
/**
 * League Table Class
 *
 * The SportsPress league table class handles individual league table data.
 *
 * @class 		SP_League_Table
 * @version     1.9.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_League_Table extends SP_Custom_Post{

	/** @var array The sort priorities array. */
	public $priorities;

	/** @var array Positions of teams in the table. */
	public $pos;

	/** @var array Inremental value for team position. */
	public $counter;

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $admin = false ) {
		$league_ids = sp_get_the_term_ids( $this->ID, 'sp_league' );
		$season_ids = sp_get_the_term_ids( $this->ID, 'sp_season' );
		$table_stats = (array)get_post_meta( $this->ID, 'sp_teams', true );
		$usecolumns = get_post_meta( $this->ID, 'sp_columns', true );
		$adjustments = get_post_meta( $this->ID, 'sp_adjustments', true );
		$select = get_post_meta( $this->ID, 'sp_select', true );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get teams automatically if set to auto
		if ( 'auto' == $select ) {
			$team_ids = array();

			$args = array(
				'post_type' => 'sp_team',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'order' => 'ASC',
				'tax_query' => array(
					'relation' => 'AND',
				),
			);

			if ( $league_ids ):
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'id',
					'terms' => $league_ids
				);
			endif;

			if ( $season_ids ):
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'id',
					'terms' => $season_ids
				);
			endif;

			$teams = get_posts( $args );

			if ( $teams && is_array( $teams ) ) {
				foreach ( $teams as $team ) {
					$team_ids[] = $team->ID;
				}
			}
		} else {
			$team_ids = (array)get_post_meta( $this->ID, 'sp_team', false );
		}

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $team_ids, $table_stats );

		// Create entry for each team in totals
		$totals = array();
		$placeholders = array();

		// Initialize incremental counter
		$this->pos = 0;
		$this->counter = 0;

		// Initialize team compare
		$this->compare = null;

		// Initialize streaks counter
		$streaks = array();

		// Initialize last counters
		$last5s = array();
		$last10s = array();

		// Initialize record counters
		$homerecords = array();
		$awayrecords = array();

		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			// Initialize team streaks counter
			$streaks[ $team_id ] = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize team last counters
			$last5s[ $team_id ] = array();
			$last10s[ $team_id ] = array();

			// Initialize team record counters
			$homerecords[ $team_id ] = array();
			$awayrecords[ $team_id ] = array();

			// Add outcome types to team last and record counters
			foreach( $outcome_labels as $key => $value ):
				$last5s[ $team_id ][ $key ] = 0;
				$last10s[ $team_id ][ $key ] = 0;
				$homerecords[ $team_id ][ $key ] = 0;
				$awayrecords[ $team_id ][ $key ] = 0;
			endforeach;

			// Initialize team totals
			$totals[ $team_id ] = array(
				'eventsplayed' => 0,
				'eventsplayed_home' => 0,
				'eventsplayed_away' => 0,
				'eventsplayed_venue' => 0,
				'eventminutes' => 0,
				'eventminutes_home' => 0,
				'eventminutes_away' => 0,
				'eventminutes_venue' => 0,
				'streak' => 0,
				'streak_home' => 0,
				'streak_away' => 0,
				'streak_venue' => 0,
			);

			foreach ( $result_labels as $key => $value ):
				$totals[ $team_id ][ $key . 'for' ] = 0;
				$totals[ $team_id ][ $key . 'for_home' ] = 0;
				$totals[ $team_id ][ $key . 'for_away' ] = 0;
				$totals[ $team_id ][ $key . 'for_venue' ] = 0;
				$totals[ $team_id ][ $key . 'against' ] = 0;
				$totals[ $team_id ][ $key . 'against_home' ] = 0;
				$totals[ $team_id ][ $key . 'against_away' ] = 0;
				$totals[ $team_id ][ $key . 'against_venue' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $team_id ][ $key ] = 0;
				$totals[ $team_id ][ $key . '_home' ] = 0;
				$totals[ $team_id ][ $key . '_away' ] = 0;
				$totals[ $team_id ][ $key . '_venue' ] = 0;
			endforeach;

			// Get static stats
			$static = get_post_meta( $team_id, 'sp_columns', true );

			if ( 'yes' == get_option( 'sportspress_team_column_editing', 'no' ) && $league_ids && $season_ids ):
				// Add static stats to placeholders
				foreach ( $league_ids as $league_id ):
					foreach ( $season_ids as $season_id ):
						$placeholders[ $team_id ] = (array) sp_array_value( sp_array_value( $static, $league_id, array() ), $season_id, array() );
					endforeach;
				endforeach;
			endif;

		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'post_status' => array( 'publish', 'future' ),
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'meta_query' => array(
				array(
					'key' => 'sp_format',
					'value' => apply_filters( 'sportspress_competitive_event_formats', array( 'league' ) ),
					'compare' => 'IN',
				),
			),
			'tax_query' => array(
				'relation' => 'AND',
			),
		);

		if ( $league_ids ):
			$args['tax_query'][] = array(
				'taxonomy' => 'sp_league',
				'field' => 'id',
				'terms' => $league_ids
			);
		endif;

		if ( $season_ids ):
			$args['tax_query'][] = array(
				'taxonomy' => 'sp_season',
				'field' => 'id',
				'terms' => $season_ids
			);
		endif;

		$args = apply_filters( 'sportspress_table_data_event_args', $args );
		
		$events = get_posts( $args );

		$e = 0;

		// Event loop
		foreach ( $events as $event ):

			$results = (array)get_post_meta( $event->ID, 'sp_results', true );
			$minutes = get_post_meta( $event->ID, 'sp_minutes', true );
			if ( $minutes === '' ) $minutes = get_option( 'sportspress_event_minutes', 90 );

			$i = 0;

			foreach ( $results as $team_id => $team_result ):

				if ( ! in_array( $team_id, $team_ids ) )
					continue;

				if ( $team_result ): foreach ( $team_result as $key => $value ):

					if ( $key == 'outcome' ):

						if ( ! is_array( $value ) ):
							$value = array( $value );
						endif;

						foreach ( $value as $outcome ):

							// Increment events played and outcome count
							if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $outcome, $totals[ $team_id ] ) ):
								$totals[ $team_id ]['eventsplayed'] ++;
								$totals[ $team_id ]['eventminutes'] += $minutes;
								$totals[ $team_id ][ $outcome ] ++;

								// Add to home or away stats
								if ( 0 === $i ):
									$totals[ $team_id ]['eventsplayed_home'] ++;
									$totals[ $team_id ]['eventminutes_home'] += $minutes;
									$totals[ $team_id ][ $outcome . '_home' ] ++;
								else:
									$totals[ $team_id ]['eventsplayed_away'] ++;
									$totals[ $team_id ]['eventminutes_away'] += $minutes;
									$totals[ $team_id ][ $outcome . '_away' ] ++;
								endif;

								// Add to venue stats
								if ( sp_is_home_venue( $team_id, $event->ID ) ):
									$totals[ $team_id ]['eventsplayed_venue'] ++;
									$totals[ $team_id ]['eventminutes_venue'] += $minutes;
									$totals[ $team_id ][ $outcome . '_venue' ] ++;
								endif;
							endif;

							if ( $outcome && $outcome != '-1' ):

								// Add to streak counter
								if ( $streaks[ $team_id ]['fire'] && ( $streaks[ $team_id ]['name'] == '' || $streaks[ $team_id ]['name'] == $outcome ) ):
									$streaks[ $team_id ]['name'] = $outcome;
									$streaks[ $team_id ]['count'] ++;
								else:
									$streaks[ $team_id ]['fire'] = 0;
								endif;

								// Add to last 5 counter if sum is less than 5
								if ( array_key_exists( $team_id, $last5s ) && array_key_exists( $outcome, $last5s[ $team_id ] ) && array_sum( $last5s[ $team_id ] ) < 5 ):
									$last5s[ $team_id ][ $outcome ] ++;
								endif;

								// Add to last 10 counter if sum is less than 10
								if ( array_key_exists( $team_id, $last10s ) && array_key_exists( $outcome, $last10s[ $team_id ] ) && array_sum( $last10s[ $team_id ] ) < 10 ):
									$last10s[ $team_id ][ $outcome ] ++;
								endif;

								// Add to home or away record
								if ( 0 === $i ) {
									if ( array_key_exists( $team_id, $homerecords ) && array_key_exists( $outcome, $homerecords[ $team_id ] ) ) {
										$homerecords[ $team_id ][ $outcome ] ++;
									}
								} else {
									if ( array_key_exists( $team_id, $awayrecords ) && array_key_exists( $outcome, $awayrecords[ $team_id ] ) ) {
										$awayrecords[ $team_id ][ $outcome ] ++;
									}
								}

							endif;

						endforeach;

					else:
						if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $key . 'for', $totals[ $team_id ] ) ):
							$totals[ $team_id ][ $key . 'for' ] += $value;
							$totals[ $team_id ][ $key . 'for' . ( $e + 1 ) ] = $value;

							// Add to home or away stats
							if ( 0 === $i ):
								$totals[ $team_id ][ $key . 'for_home' ] += $value;
							else:
								$totals[ $team_id ][ $key . 'for_away' ] += $value;
							endif;

							// Add to venue stats
							if ( sp_is_home_venue( $team_id, $event->ID ) ):
								$totals[ $team_id ][ $key . 'for_venue' ] += $value;
							endif;

							foreach( $results as $other_team_id => $other_result ):
								if ( $other_team_id != $team_id && array_key_exists( $key . 'against', $totals[ $team_id ] ) ):
									$totals[ $team_id ][ $key . 'against' ] += sp_array_value( $other_result, $key, 0 );
									$totals[ $team_id ][ $key . 'against' . ( $e + 1 ) ] = sp_array_value( $other_result, $key, 0 );

									// Add to home or away stats
									if ( 0 === $i ):
										$totals[ $team_id ][ $key . 'against_home' ] += sp_array_value( $other_result, $key, 0 );
									else:
										$totals[ $team_id ][ $key . 'against_away' ] += sp_array_value( $other_result, $key, 0 );
									endif;

									// Add to venue stats
									if ( sp_is_home_venue( $team_id, $event->ID ) ):
										$totals[ $team_id ][ $key . 'against_venue' ] += sp_array_value( $other_result, $key, 0 );
									endif;
								endif;
							endforeach;
						endif;
					endif;

				endforeach; endif;

				$i++;

			endforeach;

			$e++;

		endforeach;

		foreach ( $streaks as $team_id => $streak ):
			// Compile streaks counter and add to totals
			if ( $streak['name'] ):
				$args = array(
					'name' => $streak['name'],
					'post_type' => 'sp_outcome',
					'post_status' => 'publish',
					'posts_per_page' => 1,
					'orderby' => 'menu_order',
					'order' => 'ASC',
				);
				$outcomes = get_posts( $args );

				if ( $outcomes ):
					$outcome = reset( $outcomes );
					$abbreviation = get_post_meta( $outcome->ID, 'sp_abbreviation', true );
					if ( ! $abbreviation )
						$abbreviation = substr( $outcome->post_title, 0, 1 );
					$totals[ $team_id ]['streak'] = $abbreviation . $streak['count'];
				else:
					$totals[ $team_id ]['streak'] = null;
				endif;
			else:
				$totals[ $team_id ]['streak'] = null;
			endif;
		endforeach;

		foreach ( $last5s as $team_id => $last5 ):
			// Add last 5 to totals
			$totals[ $team_id ]['last5'] = $last5;
		endforeach;

		foreach ( $last10s as $team_id => $last10 ):
			// Add last 10 to totals
			$totals[ $team_id ]['last10'] = $last10;
		endforeach;

		foreach ( $homerecords as $team_id => $homerecord ):
			// Add home record to totals
			$totals[ $team_id ]['homerecord'] = $homerecord;
		endforeach;

		foreach ( $awayrecords as $team_id => $awayrecord ):
			// Add away record to totals
			$totals[ $team_id ]['awayrecord'] = $awayrecord;
		endforeach;

		$args = array(
			'post_type' => 'sp_column',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );

		$columns = array();
		$this->priorities = array();

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, null );
			$stat->precision = sp_array_value( sp_array_value( $meta, 'sp_precision', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

			// Add order to priorities if priority is set and does not exist in array already
			$priority = sp_array_value( sp_array_value( $meta, 'sp_priority', array() ), 0, 0 );
			if ( $priority && ! array_key_exists( $priority, $this->priorities ) ):
				$this->priorities[ $priority ] = array(
					'column' => $stat->post_name,
					'order' => sp_array_value( sp_array_value( $meta, 'sp_order', array() ), 0, 'DESC' )
				);
			endif;

		endforeach;

		// Sort priorities in descending order
		ksort( $this->priorities );

		// Initialize games back column variable
		$gb_column = null;

		// Fill in empty placeholder values for each team
		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			foreach ( $stats as $stat ):
				if ( sp_array_value( sp_array_value( $placeholders, $team_id, array() ), $stat->post_name, '' ) == '' ):

					if ( $stat->equation == null ):
						$placeholder += sp_array_value( sp_array_value( $adjustments, $team_id, array() ), $stat->post_name, null );
						if ( $placeholder == null ):
							$placeholder = '-';
						endif;
					else:
						// Solve
						$placeholder = sp_solve( $stat->equation, sp_array_value( $totals, $team_id, array() ), $stat->precision );

						if ( '$gamesback' == $stat->equation )
							$gb_column = $stat->post_name;

						if ( ! in_array( $stat->equation, array( '$gamesback', '$streak', '$last5', '$last10', '$homerecord', '$awayrecord' ) ) ):
							// Adjustments
							$adjustment = sp_array_value( $adjustments, $team_id, array() );

							if ( $adjustment != 0 ):
								$placeholder += sp_array_value( $adjustment, $stat->post_name, 0 );
								$placeholder = number_format( $placeholder, $stat->precision, '.', '' );
							endif;
						endif;
					endif;

					$placeholders[ $team_id ][ $stat->post_name ] = $placeholder;
				endif;
			endforeach;
		endforeach;

		// Find win and loss variables for games back
		$w = $l = null;
		if ( $gb_column ) {
			$args = array(
				'post_type' => 'sp_outcome',
				'numberposts' => 1,
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'sp_condition',
						'value' => '>',
					),
				),
			);
			$outcomes = get_posts( $args );

			if ( $outcomes ) {
				$outcome = reset( $outcomes );
				if ( is_array( $stats ) ) {
					foreach ( $stats as $stat ) {
						if ( '$' . $outcome->post_name == $stat->equation ) {
							$w = $stat->post_name;
						}
					}
				}
			}

			// Calculate games back
			$args = array(
				'post_type' => 'sp_outcome',
				'numberposts' => 1,
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'sp_condition',
						'value' => '<',
					),
				),
			);
			$outcomes = get_posts( $args );

			if ( $outcomes ) {
				$outcome = reset( $outcomes );
				if ( is_array( $stats ) ) {
					foreach ( $stats as $stat ) {
						if ( '$' . $outcome->post_name == $stat->equation ) {
							$l = $stat->post_name;
						}
					}
				}
			}
		}

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $team_id => $team_data ):

			// Add team name to row
			$merged[ $team_id ] = array();

			$team_data['name'] = get_the_title( $team_id );

			foreach ( $team_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $team_id, $tempdata ) && array_key_exists( $key, $tempdata[ $team_id ] ) && $tempdata[ $team_id ][ $key ] != '' ):
					$merged[ $team_id ][ $key ] = $tempdata[ $team_id ][ $key ];
				else:
					$merged[ $team_id ][ $key ] = $value;
				endif;

			endforeach;

		endforeach;

		uasort( $merged, array( $this, 'sort' ) );

		// Calculate position of teams for ties
		foreach ( $merged as $team_id => $team_columns ) {
			$merged[ $team_id ]['pos'] = $this->calculate_pos( $team_columns );
		}

		// Rearrange data array to reflect values
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;
		
		if ( $admin ):
			$this->add_gb( $placeholders, $w, $l, $gb_column );
			return array( $columns, $usecolumns, $data, $placeholders, $merged );
		else:
			$this->add_gb( $merged, $w, $l, $gb_column );
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			$labels = array_merge( array( 'pos' => __( 'Pos', 'sportspress' ), 'name' => __( 'Team', 'sportspress' ) ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}

	/**
	 * Sort the table by priorities.
	 *
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function sort( $a, $b ) {

		// Loop through priorities
		foreach( $this->priorities as $priority ):

			// Proceed if columns are not equal
			if ( sp_array_value( $a, $priority['column'], 0 ) != sp_array_value( $b, $priority['column'], 0 ) ):

				// Compare column values
				$output = sp_array_value( $a, $priority['column'], 0 ) - sp_array_value( $b, $priority['column'], 0 );

				// Flip value if descending order
				if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

				return ( $output > 0 );

			endif;

		endforeach;

		// Default sort by alphabetical
		return strcmp( sp_array_value( $a, 'name', '' ), sp_array_value( $b, 'name', '' ) );
	}

	/**
	 * Find accurate position of teams.
	 *
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function calculate_pos( $columns ) {
		$this->counter++;

		if ( 'yes' == get_option( 'sportspress_table_increment', 'no' ) ) {
			return $this->counter;
		}

		// Replace compare data and use last set
		$compare = $this->compare;
		$this->compare = $columns;

		// Loop through priorities
		foreach( $this->priorities as $priority ):

			// Proceed if columns are not equal
			if ( sp_array_value( $columns, $priority['column'], 0 ) !== sp_array_value( $compare, $priority['column'], 0 ) ):

				// Increment if not equal
				$this->pos = $this->counter;
				return $this->counter;

			endif;

		endforeach;

		// Repeat position if equal
		return $this->pos;
	}


	/**
	 * Calculate and add games back.
	 *
	 * @param array $a
	 * @param string $w
	 * @param string $l
	 * @param string $column
	 * @return null
	 */
	public function add_gb( &$a, $w = null, $l = null, $column ) {
		if ( ! is_array( $a ) ) return;
		if ( ! $w && ! $l ) return;

		foreach ( $a as $team_id => $values ) {
			if ( isset( $leader ) ) {
				$gb = ( sp_array_value( $leader, $w, 0 ) - sp_array_value( $values, $w, 0 ) + sp_array_value( $values, $l, 0 ) - sp_array_value( $leader, $l, 0 ) ) / 2;
				if ( '-' == sp_array_value( $values, $column ) && 0 !== $gb ) {
					$a[ $team_id ][ $column ] = $gb;
				}
			} else {
				$leader = $values;
			}
		}
	}
}
