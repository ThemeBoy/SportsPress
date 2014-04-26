<?php
/**
 * Player List Class
 *
 * The SportsPress player list class handles individual player list data.
 *
 * @class 		SP_Player_List
 * @version		0.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Player_List {

	/** @var int The player list (post) ID. */
	public $ID;

	/** @var object The actual post object. */
	public $post;

	/** @var array The sort priorities array. */
	public $priorities;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Player_List ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;
	}

	/**
	 * __get function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __get( $key ) {
		if ( ! isset( $key ) ):
			return $this->post;
		else:
			$value = get_post_meta( $this->ID, 'sp_' . $key, true );
		endif;

		return $value;
	}

	/**
	 * Returns formatted data
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $admin = false ) {
		$league_id = sp_get_the_term_id( $this->ID, 'sp_league', 0 );
		$div_id = sp_get_the_term_id( $this->ID, 'sp_season', 0 );
		$player_ids = (array)get_post_meta( $this->ID, 'sp_player', false );
		$list_stats = (array)get_post_meta( $this->ID, 'sp_players', true );
		$usecolumns = get_post_meta( $this->ID, 'sp_columns', true );
		$adjustments = get_post_meta( $this->ID, 'sp_adjustments', true );
		$orderby = get_post_meta( $this->ID, 'sp_orderby', true );
		$order = get_post_meta( $this->ID, 'sp_order', true );

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
