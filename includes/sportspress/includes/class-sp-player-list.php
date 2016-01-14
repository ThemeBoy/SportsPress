<?php
/**
 * Player List Class
 *
 * The SportsPress player list class handles individual player list data.
 *
 * @class 		SP_Player_List
 * @version     1.9.13
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Player_List extends SP_Custom_Post {

	/** @var array The columns array. */
	public $columns;

	/** @var array The sort priorities array. */
	public $priorities;

	/**
	 * Constructor
	 */
	public function __construct( $post ) {
		parent::__construct( $post );
		$this->columns = get_post_meta( $this->ID, 'sp_columns', true );
		if ( ! is_array( $this->columns ) ) $this->columns = array();
	}

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
		$position_ids = sp_get_the_term_ids( $this->ID, 'sp_position' );
		$team = get_post_meta( $this->ID, 'sp_team', true );
		$list_stats = (array)get_post_meta( $this->ID, 'sp_players', true );
		$adjustments = get_post_meta( $this->ID, 'sp_adjustments', true );
		$orderby = get_post_meta( $this->ID, 'sp_orderby', true );
		$order = get_post_meta( $this->ID, 'sp_order', true );
		$select = get_post_meta( $this->ID, 'sp_select', true );

		// Get labels from performance variables
		$performance_labels = (array)sp_get_var_labels( 'sp_performance' );

		// Get labels from outcome variables
		$outcome_labels = (array)sp_get_var_labels( 'sp_outcome' );

		// Get labels from result variables
		$result_labels = (array)sp_get_var_labels( 'sp_result' );

		// Get players automatically if set to auto
		if ( 'auto' == $select ) {
			$player_ids = array();

			$args = array(
				'post_type' => 'sp_player',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'meta_key' => 'sp_number',
				'orderby' => 'meta_value_num',
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

			if ( $position_ids ):
				$args['tax_query'][] = array(
					'taxonomy' => 'sp_position',
					'field' => 'id',
					'terms' => $position_ids
				);
			endif;

			if ( $team && apply_filters( 'sportspress_has_teams', true ) ):
				$args['meta_query'] = array(
					array(
						'key' => 'sp_team',
						'value' => $team
					),
				);
			endif;

			$players = get_posts( $args );

			if ( $players && is_array( $players ) ) {
				foreach ( $players as $player ) {
					$player_ids[] = $player->ID;
				}
			}
		} else {
			$player_ids = (array)get_post_meta( $this->ID, 'sp_player', false );
		}

		// Get all leagues populated with stats where available
		$tempdata = sp_array_combine( $player_ids, $list_stats );

		// Create entry for each player in totals
		$totals = array();
		$placeholders = array();

		// Initialize columns
		$columns = array();

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
			$totals[ $player_id ] = array( 'eventsattended' => 0, 'eventsplayed' => 0, 'eventsstarted' => 0, 'eventssubbed' => 0, 'eventminutes' => 0, 'streak' => 0 );

			foreach ( $performance_labels as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			foreach ( $outcome_labels as $key => $value ):
				$totals[ $player_id ][ $key ] = 0;
			endforeach;

			foreach ( $result_labels as $key => $value ):
				$totals[ $player_id ][ $key . 'for' ] = $totals[ $player_id ][ $key . 'against' ] = 0;
			endforeach;

			// Get metrics
			$metrics = (array) get_post_meta( $player_id, 'sp_metrics', true );
			foreach ( $metrics as $key => $value ):
				$adjustment = sp_array_value( sp_array_value( $adjustments, $player_id, array() ), $key, null );
				if ( $adjustment != null )
					$metrics[ $key ] += $adjustment;
			endforeach;

			// Get static stats
			$static = get_post_meta( $player_id, 'sp_statistics', true );

			// Get league and season arrays for static stats
			$static_league_ids = ( empty( $league_ids ) ? array( 0 ) : $league_ids );
			$static_season_ids = ( empty( $season_ids ) ? array( 0 ) : $season_ids );

			// Add static stats to placeholders
			if ( $static_league_ids && $static_season_ids ):
				foreach ( $static_league_ids as $league_id ):
					foreach ( $static_season_ids as $season_id ):
						$player_league_season_stats = sp_array_value( sp_array_value( $static, $league_id, array() ), $season_id, array() );
						if ( is_array( $player_league_season_stats ) ):
							foreach ( $player_league_season_stats as $key => $value ):
								$current_value = sp_array_value( sp_array_value( $placeholders, $player_id, array() ), $key, 0 );
								$placeholders[ $player_id ][ $key ] = $current_value + $value;
							endforeach;
						endif;
					endforeach;
				endforeach;
			else:
				$placeholders[ $player_id ] = sp_array_value( sp_array_value( $static, 0, array() ), 0, array() );
			endif;

			// Add metrics to placeholders
			$placeholders[ $player_id ] = array_merge( $metrics, sp_array_value( $placeholders, $player_id, array() ) );
		endforeach;

		$args = array(
			'post_type' => 'sp_event',
			'numberposts' => -1,
			'posts_per_page' => -1,
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

		$args = apply_filters( 'sportspress_list_data_event_args', $args );
		
		$events = get_posts( $args );

		// Event loop
		foreach ( $events as $i => $event ):
			$results = (array)get_post_meta( $event->ID, 'sp_results', true );
			$team_performance = get_post_meta( $event->ID, 'sp_players', true );
			$minutes = get_post_meta( $event->ID, 'sp_minutes', true );
			if ( $minutes === '' ) $minutes = get_option( 'sportspress_event_minutes', 90 );

			// Add all team performance
			if ( is_array( $team_performance ) ): foreach ( $team_performance as $team_id => $players ):
				if ( is_array( $players ) ): foreach ( $players as $player_id => $player_performance ):
					if ( array_key_exists( $player_id, $totals ) && is_array( $totals[ $player_id ] ) ):

						$player_performance = sp_array_value( $players, $player_id, array() );

						foreach ( $player_performance as $key => $value ):
							if ( 'outcome' == $key ):
								// Increment events attended, played, and started
								$totals[ $player_id ]['eventsattended'] ++;
								$totals[ $player_id ]['eventsplayed'] ++;
								$totals[ $player_id ]['eventsstarted'] ++;
								$totals[ $player_id ]['eventminutes'] += $minutes;

								// Convert to array
								if ( ! is_array( $value ) ):
									$value = array( $value );
								endif;

								foreach ( $value as $outcome ):

									if ( $outcome && $outcome != '-1' ):

										// Increment events attended and outcome count
										if ( array_key_exists( $outcome, $totals[ $player_id ] ) ):
											$totals[ $player_id ][ $outcome ] ++;
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
							elseif ( array_key_exists( $key, $totals[ $player_id ] ) ):
								$totals[ $player_id ][ $key ] += $value;
							endif;
						endforeach;

						$team_results = sp_array_value( $results, $team_id, array() );

						// Loop through home team
						foreach ( $team_results as $result_slug => $team_result ):
							if ( 'outcome' == $result_slug ):

								// Increment events attended
								$totals[ $player_id ]['eventsattended'] ++;

								// Continue with incrementing values if active in event
								if ( sp_array_value( $player_performance, 'status' ) != 'sub' || sp_array_value( $player_performance, 'sub', 0 ) ): 
									$totals[ $player_id ]['eventsplayed'] ++;
									$totals[ $player_id ]['eventminutes'] += $minutes;

									if ( sp_array_value( $player_performance, 'status' ) == 'lineup' ):
										$totals[ $player_id ]['eventsstarted'] ++;
									elseif ( sp_array_value( $player_performance, 'status' ) == 'sub' && sp_array_value( $player_performance, 'sub', 0 ) ):
										$totals[ $player_id ]['eventssubbed'] ++;
									endif;

									$value = $team_result;

									// Convert to array
									if ( ! is_array( $value ) ):
										$value = array( $value );
									endif;

									foreach ( $value as $outcome ):

										if ( $outcome && $outcome != '-1' ):

											// Increment events attended and outcome count
											if ( array_key_exists( $outcome, $totals[ $player_id ] ) ):
												$totals[ $player_id ][ $outcome ] ++;
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
							else:

								// Add to total
								$value = sp_array_value( $totals[ $player_id ], $result_slug . 'for', 0 );
								$value += $team_result;
								$totals[ $player_id ][ $result_slug . 'for' ] = $value;

								// Add subset
								$totals[ $player_id ][ $result_slug . 'for' . ( $i + 1 ) ] = $team_result;
							endif;
						endforeach;

						// Loop through away teams
						if ( sizeof( $results ) ):
							foreach ( $results as $id => $team_results ):
								if ( $team_id == $id ) continue;
								unset( $team_results['outcome'] );
								foreach ( $team_results as $result_slug => $team_result ):

									// Add to total
									$value = sp_array_value( $totals[ $player_id ], $result_slug . 'against', 0 );
									$value += $team_result;
									$totals[ $player_id ][ $result_slug . 'against' ] = $value;

									// Add subset
									$totals[ $player_id ][ $result_slug . 'against' . ( $i + 1 ) ] = $team_result;
								endforeach;
							endforeach;
						endif;
					endif;
				endforeach; endif;
			endforeach; endif;
			$i++;
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
					$abbreviation = sp_get_abbreviation( $outcome->ID );
					if ( empty( $abbreviation ) ) $abbreviation = strtoupper( substr( $outcome->post_title, 0, 1 ) );
					$totals[ $player_id ]['streak'] = $abbreviation . $streak['count'];
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
			'post_type' => array( 'sp_performance', 'sp_metric', 'sp_statistic' ),
			'numberposts' => -1,
			'posts_per_page' => -1,
	  		'orderby' => 'menu_order',
	  		'order' => 'ASC'
		);
		$stats = get_posts( $args );

		foreach ( $stats as $stat ):

			// Get post meta
			$meta = get_post_meta( $stat->ID );

			// Add equation to object
			if ( $stat->post_type == 'sp_metric' ):
				$stat->equation = null;
			else:
				$stat->equation = sp_array_value( sp_array_value( $meta, 'sp_equation', array() ), 0, 0 );
			endif;

			// Add precision to object
			$stat->precision = sp_array_value( sp_array_value( $meta, 'sp_precision', array() ), 0, 0 );

			// Add column name to columns
			$columns[ $stat->post_name ] = $stat->post_title;

		endforeach;

		// Fill in empty placeholder values for each player
		foreach ( $player_ids as $player_id ):
			if ( ! $player_id )
				continue;

			$placeholders[ $player_id ] = array_merge( sp_array_value( $totals, $player_id, array() ), array_filter( sp_array_value( $placeholders, $player_id, array() ) ) );

			foreach ( $stats as $stat ):
				if ( sp_array_value( $placeholders[ $player_id ], $stat->post_name, '' ) == '' ):

					if ( $stat->equation === null ):
						$placeholder = sp_array_value( sp_array_value( $adjustments, $player_id, array() ), $stat->post_name, null );
						if ( $placeholder == null ):
							$placeholder = '-';
						endif;
					else:
						// Solve
						$placeholder = sp_solve( $stat->equation, $placeholders[ $player_id ], $stat->precision );

						// Adjustments
						$adjustment = sp_array_value( $adjustments, $player_id, array() );

						if ( $adjustment != 0 ):
							$placeholder += sp_array_value( $adjustment, $stat->post_name, 0 );
							$placeholder = number_format( $placeholder, $stat->precision, '.', '' );
						endif;
					endif;

					$placeholders[ $player_id ][ $stat->post_name ] = $placeholder;
				endif;
			endforeach;

		endforeach;

		// Merge the data and placeholders arrays
		$merged = array();

		foreach( $placeholders as $player_id => $player_data ):

			// Add player number and name to row
			$merged[ $player_id ] = array();
			if ( in_array( 'number', $this->columns ) ):
				$player_data['number'] = get_post_meta( $player_id, 'sp_number', true );
			endif;
			
			$player_data['name'] = get_the_title( $player_id );
			
			if ( in_array( 'team', $this->columns ) ):
				$player_data['team'] = get_post_meta( $player_id, 'sp_team', true );
			endif;
			
			if ( in_array( 'position', $this->columns ) ):
				$player_data['position'] = null;
			endif;

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
			$this->priorities = array(
				array(
					'key' => $orderby,
					'order' => $order,
				),
			);
			uasort( $merged, array( $this, 'sort' ) );
		endif;

		// Rearrange data array to reflect values
		$data = array();
		foreach( $merged as $key => $value ):
			$data[ $key ] = $tempdata[ $key ];
		endforeach;
		
		if ( $admin ):
			$labels = array();
			foreach( $this->columns as $key ):
				if ( $key == 'number' ):
					$labels[ $key ] = '#';
				elseif ( $key == 'team' && apply_filters( 'sportspress_has_teams', true ) ):
					$labels[ $key ] = __( 'Team', 'sportspress' );
				elseif ( $key == 'position' ):
					$labels[ $key ] = __( 'Position', 'sportspress' );
				elseif ( array_key_exists( $key, $columns ) ):
					$labels[ $key ] = $columns[ $key ];
				endif;
			endforeach;
			return array( $labels, $data, $placeholders, $merged, $orderby );
		else:
			if ( ! is_array( $this->columns ) )
				$this->columns = array();
			foreach ( $columns as $key => $label ):
				if ( ! in_array( $key, $this->columns ) ):
					unset( $columns[ $key ] );
				endif;
			endforeach;

			$labels = array();
			if ( in_array( 'number', $this->columns ) ) $labels['number'] = '#';
			$labels['name'] = __( 'Player', 'sportspress' );
			if ( in_array( 'team', $this->columns ) && apply_filters( 'sportspress_has_teams', true ) ) $labels['team'] = __( 'Team', 'sportspress' );
			if ( in_array( 'position', $this->columns ) ) $labels['position'] = __( 'Position', 'sportspress' );

			$merged[0] = array_merge( $labels, $columns );
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
		if ( is_array( $this->priorities ) ) : foreach( $this->priorities as $priority ):

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
