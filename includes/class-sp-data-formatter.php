<?php
/**
 * SportsPress data formatter
 *
 * The SportsPress data formatter class formats data for use in admin and templates.
 *
 * @class 		SP_Data_Formatter
 * @version		0.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Data_Formatter {

	private $table_priorities;
	private $list_priorities;

	/**
	 * Formats data for team columns
	 */
	function team_columns( $post_id, $league_id, $admin = false ) {
		$seasons = (array)get_the_terms( $post_id, 'sp_season' );
		$columns = (array)get_post_meta( $post_id, 'sp_columns', true );
		$leagues_seasons = sp_array_value( (array)get_post_meta( $post_id, 'sp_leagues', true ), $league_id, array() );

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
						'value' => $post_id
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
						if ( $team_id == $post_id ):
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

	/**
	 * Formats data for league tables
	 */
	public function league_table( $post_id, $admin = false ) {
		$league_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $post_id, 'sp_season', 0 );
		$team_ids = (array)get_post_meta( $post_id, 'sp_team', false );
		$table_stats = (array)get_post_meta( $post_id, 'sp_teams', true );
		$usecolumns = get_post_meta( $post_id, 'sp_columns', true );
		$adjustments = get_post_meta( $post_id, 'sp_adjustments', true );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $team_ids, $table_stats );

		// Create entry for each team in totals
		$totals = array();
		$placeholders = array();

		// Initialize streaks counter
		$streaks = array();

		// Initialize last counters
		$last5s = array();
		$last10s = array();

		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			// Initialize team streaks counter
			$streaks[ $team_id ] = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize team last counters
			$last5s[ $team_id ] = array();
			$last10s[ $team_id ] = array();

			// Add outcome types to team last counters
			foreach( $outcome_labels as $key => $value ):
				$last5s[ $team_id ][ $key ] = 0;
				$last10s[ $team_id ][ $key ] = 0;
			endforeach;

			// Initialize team totals
			$totals[ $team_id ] = array( 'eventsplayed' => 0, 'streak' => 0 );

			foreach ( $result_labels as $key => $value ):
				$totals[ $team_id ][ $key . 'for' ] = 0;
				$totals[ $team_id ][ $key . 'against' ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $team_id ][ $key ] = 0;
			endforeach;

			// Get static stats
			$static = get_post_meta( $team_id, 'sp_columns', true );

			// Add static stats to placeholders
			$placeholders[ $team_id ] = sp_array_value( $static, $div_id, array() );

		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'order' => 'ASC',
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
				)
			)
		);
		$events = get_posts( $args );

		// Event loop
		foreach ( $events as $event ):

			$results = (array)get_post_meta( $event->ID, 'sp_results', true );

			foreach ( $results as $team_id => $team_result ):

				if ( ! in_array( $team_id, $team_ids ) )
					continue;

				foreach ( $team_result as $key => $value ):

					if ( $key == 'outcome' ):

						if ( ! is_array( $value ) ):
							$value = array( $value );
						endif;

						foreach ( $value as $outcome ):

							// Increment events played and outcome count
							if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $outcome, $totals[ $team_id ] ) ):
								$totals[ $team_id ]['eventsplayed']++;
								$totals[ $team_id ][ $outcome ]++;
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

							endif;

						endforeach;

					else:
						if ( array_key_exists( $team_id, $totals ) && is_array( $totals[ $team_id ] ) && array_key_exists( $key . 'for', $totals[ $team_id ] ) ):
							$totals[ $team_id ][ $key . 'for' ] += $value;
							foreach( $results as $other_team_id => $other_result ):
								if ( $other_team_id != $team_id && array_key_exists( $key . 'against', $totals[ $team_id ] ) ):
									$totals[ $team_id ][ $key . 'against' ] += sp_array_value( $other_result, $key, 0 );
								endif;
							endforeach;
						endif;
					endif;

				endforeach;

			endforeach;

		endforeach;

		foreach ( $streaks as $team_id => $streak ):
			// Compile streaks counter and add to totals
			if ( $streak['name'] ):
				$args = array(
					'name' => $streak['name'],
					'post_type' => 'sp_outcome',
					'post_status' => 'publish',
					'posts_per_page' => 1
				);
				$outcomes = get_posts( $args );

				if ( $outcomes ):
					$outcome = reset( $outcomes );
					$totals[ $team_id ]['streak'] = $outcome->post_title . $streak['count'];
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

		$args = array(
			'post_type' => 'sp_column',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );

		$columns = array();
		$this->table_priorities = array();

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );
			$stat->precision = sp_array_value( sp_array_value( $meta, 'sp_precision', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

			// Add order to priorities if priority is set and does not exist in array already
			$priority = sp_array_value( sp_array_value( $meta, 'sp_priority', array() ), 0, 0 );
			if ( $priority && ! array_key_exists( $priority, $this->table_priorities ) ):
				$this->table_priorities[ $priority ] = array(
					'column' => $stat->post_name,
					'order' => sp_array_value( sp_array_value( $meta, 'sp_order', array() ), 0, 'DESC' )
				);
			endif;

		endforeach;

		// Sort priorities in descending order
		ksort( $this->table_priorities );

		// Fill in empty placeholder values for each team
		foreach ( $team_ids as $team_id ):
			if ( ! $team_id )
				continue;

			foreach ( $stats as $stat ):
				if ( sp_array_value( $placeholders[ $team_id ], $stat->post_name, '' ) == '' ):

					// Solve
					$placeholder = sp_solve( $stat->equation, sp_array_value( $totals, $team_id, array() ), $stat->precision );

					// Adjustments
					$placeholder += sp_array_value( sp_array_value( $adjustments, $team_id, array() ), $stat->post_name, 0 );

					$placeholders[ $team_id ][ $stat->post_name ] = $placeholder;
				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $team_id => $team_data ):

			// Add team name to row
			$merged[ $team_id ] = array();

			$team_data['name'] = get_the_title( $team_id );

			foreach( $team_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $team_id, $tempdata ) && array_key_exists( $key, $tempdata[ $team_id ] ) && $tempdata[ $team_id ][ $key ] != '' ):
					$merged[ $team_id ][ $key ] = $tempdata[ $team_id ][ $key ];
				else:
					$merged[ $team_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		uasort( $merged, array( $this, 'sort_table' ) );

		// Rearrange data array to reflect values
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;
		
		if ( $admin ):
			return array( $columns, $usecolumns, $data, $placeholders, $merged );
		else:
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $usecolumns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;
			$labels = array_merge( array( 'name' => SP()->text->string('Team') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}

	/**
	 * Formats data for player lists
	 */
	public static function player_list( $post_id, $admin = false ) {
		$league_id = sp_get_the_term_id( $post_id, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $post_id, 'sp_season', 0 );
		$player_ids = (array)get_post_meta( $post_id, 'sp_player', false );
		$list_stats = (array)get_post_meta( $post_id, 'sp_players', true );
		$usecolumns = get_post_meta( $post_id, 'sp_columns', true );
		$adjustments = get_post_meta( $post_id, 'sp_adjustments', true );
		$orderby = get_post_meta( $post_id, 'sp_orderby', true );
		$order = get_post_meta( $post_id, 'sp_order', true );

		// Get labels from performance variables
		$performance_labels = (array)sp_get_var_labels( 'sp_performance' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $player_ids, $list_stats );

		// Create entry for each player in totals
		$totals = array();
		$placeholders = array();

		// Initialize streaks counter
		$streaks = array();

		// Initialize last counters
		$last5s = array();
		$last10s = array();

		foreach ( $player_ids as $player_id ):
			if ( ! $player_id )
				continue;

			// Initialize player streaks counter
			$streaks[ $player_id ] = array( 'name' => '', 'count' => 0, 'fire' => 1 );

			// Initialize player last counters
			$last5s[ $player_id ] = array();
			$last10s[ $player_id ] = array();

			// Add outcome types to player last counters
			foreach( $outcome_labels as $key => $value ):
				$last5s[ $player_id ][ $key ] = 0;
				$last10s[ $player_id ][ $key ] = 0;
			endforeach;

			// Initialize player totals
			$totals[ $player_id ] = array( 'eventsattended' => 0, 'eventsplayed' => 0, 'streak' => 0 );

			foreach ( $performance_labels as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			// Get static stats
			$static = get_post_meta( $player_id, 'sp_statistics', true );

			// Add static stats to placeholders
			$placeholders[ $player_id ] = sp_array_value( sp_array_value( $static, $league_id, array() ), $div_id, array() );

		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'order' => 'ASC',
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
				)
			)
		);
		$events = get_posts( $args );

		// Event loop
		foreach ( $events as $event ):
			$results = (array)get_post_meta( $event->ID, 'sp_results', true );
			$team_performance = (array)get_post_meta( $event->ID, 'sp_players', true );

			// Add all team performance
			foreach ( $team_performance as $team_id => $players ):
				foreach( $players as $player_id => $player_performance ):
					if ( array_key_exists( $player_id, $totals ) && is_array( $totals[ $player_id ] ) ):

						$player_performance = sp_array_value( $players, $player_id, array() );

						foreach ( $player_performance as $key => $value ):
							if ( array_key_exists( $key, $totals[ $player_id ] ) ):
								$totals[ $player_id ][ $key ] += $value;
							endif;
						endforeach;

						$team_results = sp_array_value( $results, $team_id, array() );

						// Find the outcome
						if ( array_key_exists( 'outcome', $team_results ) ):

							$value = $team_results['outcome'];

							// Convert to array
							if ( ! is_array( $value ) ):
								$value = array( $value );
							endif;

							foreach ( $value as $outcome ):

								if ( $outcome && $outcome != '-1' ):

									// Increment events attended and outcome count
									if ( array_key_exists( $outcome, $totals[ $player_id ] ) ):
										$totals[ $player_id ]['eventsattended']++;
										$totals[ $player_id ][ $outcome ]++;

										// Increment events played if active in event
										if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
											$totals[ $player_id ]['eventsplayed']++;
										endif;
									endif;

									// Add to streak counter
									if ( $streaks[ $player_id ]['fire'] && ( $streaks[ $player_id ]['name'] == '' || $streaks[ $player_id ]['name'] == $outcome ) ):
										$streaks[ $player_id ]['name'] = $outcome;
										$streaks[ $player_id ]['count'] ++;
									else:
										$streaks[ $player_id ]['fire'] = 0;
									endif;

									// Add to last 5 counter if sum is less than 5
									if ( array_key_exists( $player_id, $last5s ) && array_key_exists( $outcome, $last5s[ $player_id ] ) && array_sum( $last5s[ $player_id ] ) < 5 ):
										$last5s[ $player_id ][ $outcome ] ++;
									endif;

									// Add to last 10 counter if sum is less than 10
									if ( array_key_exists( $player_id, $last10s ) && array_key_exists( $outcome, $last10s[ $player_id ] ) && array_sum( $last10s[ $player_id ] ) < 10 ):
										$last10s[ $player_id ][ $outcome ] ++;
									endif;

								endif;

							endforeach;

						endif;

					endif;

				endforeach;
			endforeach;

		endforeach;

		foreach ( $streaks as $player_id => $streak ):
			// Compile streaks counter and add to totals
			if ( $streak['name'] ):
				$args = array(
					'name' => $streak['name'],
					'post_type' => 'sp_outcome',
					'post_status' => 'publish',
					'posts_per_page' => 1
				);
				$outcomes = get_posts( $args );

				if ( $outcomes ):
					$outcome = reset( $outcomes );
					$totals[ $player_id ]['streak'] = $outcome->post_title . $streak['count'];
				else:
					$totals[ $player_id ]['streak'] = null;
				endif;
			else:
				$totals[ $player_id ]['streak'] = null;
			endif;
		endforeach;

		foreach ( $last5s as $player_id => $last5 ):
			// Add last 5 to totals
			$totals[ $player_id ]['last5'] = $last5;
		endforeach;

		foreach ( $last10s as $player_id => $last10 ):
			// Add last 10 to totals
			$totals[ $player_id ]['last10'] = $last10;
		endforeach;

		$args = array(
			'post_type' => 'sp_statistic',
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );

		$columns = array();

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );
			$stat->precision = sp_array_value( sp_array_value( $meta, 'sp_precision', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

		endforeach;

		// Fill in empty placeholder values for each player
		foreach ( $player_ids as $player_id ):
			if ( ! $player_id )
				continue;

			foreach ( $stats as $stat ):
				if ( sp_array_value( $placeholders[ $player_id ], $stat->post_name, '' ) == '' ):

					// Solve
					$placeholder = sp_solve( $stat->equation, sp_array_value( $totals, $player_id, array() ), $stat->precision );

					// Adjustments
					$placeholder += sp_array_value( sp_array_value( $adjustments, $player_id, array() ), $stat->post_name, 0 );

					$placeholders[ $player_id ][ $stat->post_name ] = $placeholder;
				endif;
			endforeach;
		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $player_id => $player_data ):

			// Add player name to row
			$merged[ $player_id ] = array();

			$player_data['name'] = get_the_title( $player_id );

			foreach( $player_data as $key => $value ):

				// Use static data if key exists and value is not empty, else use placeholder
				if ( array_key_exists( $player_id, $tempdata ) && array_key_exists( $key, $tempdata[ $player_id ] ) && $tempdata[ $player_id ][ $key ] != '' ):
					$merged[ $player_id ][ $key ] = $tempdata[ $player_id ][ $key ];
				else:
					$merged[ $player_id ][ $key ] = $value;
				endif;

			endforeach;
		endforeach;

		if ( $orderby != 'number' || $order != 'ASC' ):
			global $sportspress_statistic_priorities;
			$sportspress_statistic_priorities = array(
				array(
					'key' => $orderby,
					'order' => $order,
				),
			);
			uasort( $merged, array( $this, 'sort_list' ) );
		endif;

		// Rearrange data array to reflect values
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;
		
		if ( $admin ):
			return array( $columns, $usecolumns, $data, $placeholders, $merged );
		else:
			if ( ! is_array( $usecolumns ) )
				$usecolumns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $usecolumns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;
			$labels = array_merge( array( 'name' => SP()->text->string('Player') ), $columns );
			$merged[0] = $labels;
			return $merged;
		endif;
	}

	private function sort_table( $a, $b ) {

		// Loop through priorities
		foreach( $this->table_priorities as $priority ):

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

	private function sort_list ( $a, $b ) {

		// Loop through priorities
		if ( is_array( $this->list_priorities ) ) : foreach( $this->list_priorities as $priority ):

			// Proceed if columns are not equal
			if ( sp_array_value( $a, $priority['key'], 0 ) != sp_array_value( $b, $priority['key'], 0 ) ):

				if ( $priority['key'] == 'name' ):

					$output = strcmp( sp_array_value( $a, 'name', null ), sp_array_value( $b, 'name', null ) );

				else:

					// Compare performance values
					$output = sp_array_value( $a, $priority['key'], 0 ) - sp_array_value( $b, $priority['key'], 0 );

				endif;

				// Flip value if descending order
				if ( $priority['order'] == 'DESC' ) $output = 0 - $output;

				return ( $output > 0 );

			endif;

		endforeach; endif; 

		// Default sort by number
		return sp_array_value( $a, 'number', 0 ) - sp_array_value( $b, 'number', 0 );
	}
}
