<?php
/**
 * Team Class
 *
 * The SportsPress team class handles individual team data.
 *
 * @class 		SP_Team
 * @version		2.7.1
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
			'order' => 'DESC',
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
	public function columns( $league_id ) {
		$seasons = (array)get_the_terms( $this->ID, 'sp_season' );
		$columns = (array)get_post_meta( $this->ID, 'sp_columns', true );

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

		$div_ids[] = 0;
		$season_names[ 0 ] = __( 'Total', 'sportspress' );

		$data = array();

		// Get all seasons populated with data where available
		$data = sp_array_combine( $div_ids, sp_array_value( $columns, $league_id, array() ) );

		// Get equations from column variables
		$equations = sp_get_var_equations( 'sp_column' );

		// Initialize placeholders array
		$placeholders = array();

		foreach ( $div_ids as $div_id ):

			$totals = array(
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
				'last5' => null,
				'last10' => null,
				'homerecord' => null,
				'awayrecord' => null
			);

			foreach ( $result_labels as $key => $value ):
				$totals[ $key . 'for' ] = 0;
				$totals[ $key . 'for_home' ] = 0;
				$totals[ $key . 'for_away' ] = 0;
				$totals[ $key . 'for_venue' ] = 0;
				$totals[ $key . 'against' ] = 0;
				$totals[ $key . 'against_home' ] = 0;
				$totals[ $key . 'against_away' ] = 0;
				$totals[ $key . 'against_venue' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $key ] = 0;
				$totals[ $key . '_home' ] = 0;
				$totals[ $key . '_away' ] = 0;
				$totals[ $key . '_venue' ] = 0;
			endforeach;

			// Initialize streaks counter
			$streak = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize last counters
			$last5 = array();
			$last10 = array();

			// Initialize record counters
			$homerecord = array();
			$awayrecord = array();

			// Add outcome types to last and record counters
			foreach( $outcome_labels as $key => $value ):
				$last5[ $key ] = 0;
				$last10[ $key ] = 0;
				$homerecord[ $key ] = 0;
				$awayrecord[ $key ] = 0;
			endforeach;

			// Get all events involving the team in current season
			$args = array(
				'post_type' => 'sp_event',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'post_date',
				'order' => 'DESC',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'sp_team',
						'value' => $this->ID
					),
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

			if ( $league_id ):
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_league',
					'field' => 'term_id',
					'terms' => $league_id
				);
			endif;

			if ( $div_id ):
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_season',
					'field' => 'term_id',
					'terms' => $div_id
				);
			endif;

			$args = apply_filters( 'sportspress_team_data_event_args', $args );

			$events = get_posts( $args );

			$e = 0;

			foreach( $events as $event ):
				$results = (array)get_post_meta( $event->ID, 'sp_results', true );
				$minutes = get_post_meta( $event->ID, 'sp_minutes', true );
				if ( $minutes === '' ) $minutes = get_option( 'sportspress_event_minutes', 90 );

				$i = 0;

				foreach ( $results as $team_id => $team_result ):
					if ( is_array( $team_result ) ): foreach ( $team_result as $key => $value ):
						if ( $team_id == $this->ID ):
							if ( $key == 'outcome' ):

								// Convert to array
								if ( ! is_array( $value ) ):
									$value = array( $value );
								endif;

								foreach( $value as $outcome ):

									// Increment events played and outcome count
									if ( array_key_exists( $outcome, $totals ) ):
										$totals['eventsplayed'] ++;
										$totals['eventminutes'] += $minutes;
										$totals[ $outcome ] ++;

										// Add to home or away stats
										if ( 0 === $i ):
											$totals['eventsplayed_home'] ++;
											$totals['eventminutes_home'] += $minutes;
											$totals[ $outcome . '_home' ] ++;
										else:
											$totals['eventsplayed_away'] ++;
											$totals['eventminutes_away'] += $minutes;
											$totals[ $outcome . '_away' ] ++;
										endif;

										// Add to venue stats
										if ( sp_is_home_venue( $team_id, $event->ID ) ):
											$totals['eventsplayed_venue'] ++;
											$totals['eventminutes_venue'] += $minutes;
											$totals[ $outcome . '_venue' ] ++;
										endif;
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

										// Add to home or away record
										if ( 0 === $i ) {
											if ( array_key_exists( $outcome, $homerecord ) ) {
												$homerecord[ $outcome ] ++;
											}
										} else {
											if ( array_key_exists( $outcome, $awayrecord ) ) {
												$awayrecord[ $outcome ] ++;
											}
										}

									endif;

								endforeach;

							else:

								$value = floatval( $value );

								if ( array_key_exists( $key . 'for', $totals ) ):
									$totals[ $key . 'for' ] += $value;
									$totals[ $key . 'for' . ( $e + 1 ) ] = $value;

									// Add to home or away stats
									if ( 0 === $i ):
										$totals[ $key . 'for_home' ] += $value;
									else:
										$totals[ $key . 'for_away' ] += $value;
									endif;

									// Add to venue stats
									if ( sp_is_home_venue( $team_id, $event->ID ) ):
										$totals[ $key . 'for_venue' ] += $value;
									endif;
								endif;
							endif;
						else:
							if ( $key != 'outcome' ):

								$value = floatval( $value );

								if ( array_key_exists( $key . 'against', $totals ) ):
									$totals[ $key . 'against' ] += $value;
									$totals[ $key . 'against' . ( $e + 1 ) ] = $value;

									// Add to home or away stats
									if ( 0 === $i ):
										$totals[ $key . 'against_home' ] += $value;
									else:
										$totals[ $key . 'against_away' ] += $value;
									endif;

									// Add to venue stats
									if ( sp_is_home_venue( $team_id, $event->ID ) ):
										$totals[ $key . 'against_venue' ] += $value;
									endif;
								endif;
							endif;
						endif;
					endforeach; endif;
					$i++;
				endforeach;
				$e++;
			endforeach;

			// Compile streaks counter and add to totals
			$args=array(
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
					$abbreviation = sp_substr( $outcome->post_title, 0, 1 );
				$totals['streak'] = $abbreviation . $streak['count'];
			endif;

			// Add last and record counters to totals
			$totals['last5'] = $last5;
			$totals['last10'] = $last10;
			$totals['homerecord'] = $homerecord;
			$totals['awayrecord'] = $awayrecord;

			// Generate array of placeholder values for each league
			$placeholders[ $div_id ] = array();
			foreach ( $equations as $key => $value ):
				if ( '$gamesback' == $value['equation'] ) {
					$placeholders[ $div_id ][ $key ] = __( '(Auto)', 'sportspress' );
				} else {
					$placeholders[ $div_id ][ $key ] = sp_solve( $value['equation'], $totals, $value['precision'] );
				}
			endforeach;

		endforeach;

		// Get columns from column variables
		$columns = sp_get_var_labels( 'sp_column' );

		return array( $columns, $data, $placeholders );
	}

	/**
	 * Returns staff members
	 *
	 * @access public
	 * @return array
	 */
	public function staff( $admin = false ) {
		if ( ! $this->ID ) return null;

		$args = array(
			'post_type' => 'sp_staff',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_key' => 'sp_team',
			'meta_value' => $this->ID,
		);
		$members = get_posts( $args );

		$checked = (array) get_post_meta( $this->ID, 'sp_staff' );

		if ( $admin ):
			return array( $members, $checked );
		else:
			foreach ( $members as $key => $member ):
				if ( ! in_array( $member->ID, $checked ) ):
					unset( $members[ $key ] );
				endif;
			endforeach;
			return $members;
		endif;
	}

	/**
	 * Returns player lists
	 *
	 * @access public
	 * @return array
	 */
	public function lists( $admin = false ) {
		if ( ! $this->ID ) return null;

		$args = array(
			'post_type' => 'sp_list',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'sp_team',
					'value' => $this->ID,
				),
				array(
					'key' => 'sp_team',
					'value' => '0',
				),
			),
		);
		$lists = get_posts( $args );

		$checked = (array) get_post_meta( $this->ID, 'sp_list' );

		if ( $admin ):
			return array( $lists, $checked );
		else:
			foreach ( $lists as $key => $list ):
				if ( ! in_array( $list->ID, $checked ) ):
					unset( $lists[ $key ] );
				endif;
			endforeach;
			return $lists;
		endif;
	}

	/**
	 * Returns league tables
	 *
	 * @access public
	 * @return array
	 */
	public function tables( $admin = false ) {
		if ( ! $this->ID ) return null;

		$league_ids = sp_get_the_term_ids( $this->ID, 'sp_league' );
		$season_ids = sp_get_the_term_ids( $this->ID, 'sp_season' );

		$args = array(
			'post_type' => 'sp_table',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key' => 'sp_select',
					'value' => 'manual',
				),
				array(
					'key' => 'sp_team',
					'value' => $this->ID,
				),
			),
		);
		$tables_by_id = get_posts( $args );

		$args = array(
			'post_type' => 'sp_table',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
				array(
					'key' => 'sp_select',
					'value' => 'auto',
				),
			),
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'sp_league',
					'field' => 'term_id',
					'terms' => $league_ids,
				),
				array(
					'taxonomy' => 'sp_season',
					'field' => 'term_id',
					'terms' => $season_ids,
				),
			),
		);
		$tables_by_terms = get_posts( $args );
		
		$tables = array_merge( $tables_by_terms, $tables_by_id );

		$checked = (array) get_post_meta( $this->ID, 'sp_table' );

		if ( $admin ):
			return array( $tables, $checked );
		else:
			foreach ( $tables as $key => $table ):
				if ( ! in_array( $table->ID, $checked ) ):
					unset( $tables[ $key ] );
				endif;
			endforeach;
			return $tables;
		endif;
	}
}
