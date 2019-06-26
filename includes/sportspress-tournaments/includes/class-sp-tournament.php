<?php
/**
 * Tournament Class
 *
 * The SportsPress tournament class handles individual tournament data.
 *
 * @class 		SP_Tournament
 * @version		2.6.9
 * @package		SportsPress_Tournaments
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Tournament {

	/** @var int The post ID. */
	public $ID;

	/** @var object The actual post object. */
	public $post;

	/**
	 * Constructor
	 */
	public function __construct( $post ) {
		if ( $post instanceof WP_Post || $post instanceof SP_Custom_Post ):
			$this->ID   = absint( $post->ID );
			$this->post = $post;
		else:
			$this->ID  = absint( $post );
			$this->post = get_post( $this->ID );
		endif;
	}

	/**
	 * __isset function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return bool
	 */
	public function __isset( $key ) {
		return metadata_exists( 'post', $this->ID, 'sp_' . $key );
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
	 * Get the post data.
	 *
	 * @access public
	 * @return object
	 */
	public function get_post_data() {
		return $this->post;
	}
	
	/**
	 * Returns labels.
	 *
	 * @access public
	 * @return array
	 */
	public function labels( $type = 'single' ) {
		
		// Get post meta key for labels based on type
		switch ( $type ) {
			case 'losers':
				$key = 'sp_loser_labels';
				break;
			case 'finals':
				$key = 'sp_final_labels';
				break;
			default:
				$key = 'sp_labels';
		}

		$labels = ( array ) get_post_meta( $this->ID, $key, true );
		
		// Assign default labels
		for ( $i = 0; $i < sizeof( $labels ); $i++ ) {
			if ( isset( $labels[ $i ] ) && '' === $labels[ $i ] ) {
				$labels[ $i ] = sprintf( __( 'Round %s', 'sportspress' ), $i + 1 );
			}
		}
		
		return $labels;
	}

	/**
	 * Returns formatted data.
	 *
	 * @access public
	 * @param bool $admin
	 * @return array
	 */
	public function data( $layout = 'bracket', $admin = false, $type = 'single' ) {
	
		$reverse_teams = get_option( 'sportspress_event_reverse_teams', 'no' ) === 'yes' ? true : false;
		
		$labels = $this->labels( $type );
		
		// Get post meta key for raw data based on type
		switch ( $type ) {
			case 'losers':
				$key = 'sp_loser';
				break;
			case 'finals':
				$key = 'sp_final';
				break;
			default:
				$key = 'sp_event';
		}

		// Get events
		$events = get_post_meta( $this->ID, $key, false );

		// Get raw data
		$raw = (array) get_post_meta( $this->ID, $key . 's', true );
		
		// Get number of rounds
		$rounds = get_post_meta( $this->ID, 'sp_rounds', true );
		if ( $rounds === '' ) $rounds = 3;

		if ( $rounds < 2 )
			$layout = 'bracket';

		// Determine teams proceeding to next round
		if ( sizeof( $events ) !== sizeof( array_filter( $events ) ) ) {
			$offset = pow( 2, $rounds - 1 );
			for ( $i = $offset; $i < sizeof( $events ); $i++ ) {
				// Get the event
				$event = $events[ $i ];

				// If event is set, nothing to see here
				if ( $event ) continue;

				// Initialize event array
				$events[ $i ] = array();

				// Look at previous events
				for ( $h = 0; $h < 2; $h++ ) {
					// Get previous event
					$j = ( $i - $offset ) * 2 + $h;
					$prev = sp_array_value( $events, $j, 0 );

					// If no previous event, move right along
					if ( ! $prev ) continue;

					// Get winner of previous event
					$winner = sp_get_winner( $prev );

					$events[ $i ][] = $winner;
				}
			}
		}

		// Initialize counter
		$counter = array_fill( 0, $rounds, 0 );

		// Initialize reference
		$reference = array(
			0 => array(
				'margin' => 0,
				'spacing' => 1,
				'height' => 3,
			)
		);

		// Initialize rows
		$rows = 5;

		// Loop through rounds
		for ( $i = 1; $i < $rounds; $i++ ):

			// Add margin, spacing, and height to reference array
			$margin = pow( 2, $i ) + pow( 2, $i - 1 ) - 1;
			$spacing = pow( 2, $i + 1 ) + pow( 2, $i ) - 1;
			$reference[ $i ] = array(
				'margin' => $margin,
				'spacing' => $spacing,
				'height' => $spacing,
			);

		endfor;

		// Update total rows
		$rows = pow( 2, $rounds + 1 ) + pow( 2, $rounds ) - 1;
		$maxrows = $rows;

		// Adjust for centered layout
		if ( 'center' == $layout ) {
			$columns = $rounds * 2 - 1;

			// Repeat column labels
			for ( $l = 0; $l < $rounds - 1; $l++ ) {
				$label = $labels[ $l ];
				if ( $label == null ) {
					$labels[ 2 * $rounds - 2 - $l ] = sprintf( __( 'Round %s', 'sportspress' ), $l + 1 );
				} else {
					$labels[ 2 * $rounds - 2 - $l ] = $label;
				}
			}

			// Adjust middle column reference
			if ( $rounds < 3 ) {
				$reference[ $rounds - 1 ]['margin'] = $reference[0]['margin'] + 1;
				$reference[ $rounds - 1 ]['height'] = $reference[0]['spacing'];
			} else {
				$reference[ $rounds - 1 ]['margin'] = $reference[ $rounds - 3 ]['margin'] + $reference[ $rounds - 3 ]['height'] + 1;
				$reference[ $rounds - 1 ]['height'] = $reference[ $rounds - 3 ]['spacing'];
			}

			$maxrows = ( $rows - 1 ) / 2;
		} else {
			$columns = $rounds;
		}

		// Initialize data
		$data = array();

		// Initialize rows
		$row = 0;

		// Generate sequence for finals bracket
		if ( 'finals' === $type ) {
			$last = pow( 2, $rounds - 1 ) / 2 - 1;
			$adjustment = pow( 2, $rounds - 1 ) / 2 + pow( 2, $rounds - 2 ) / 2;
			for ( $i = 0; $i < $rounds; $i++ ) {
				$sequence[] = $last;
				$last = $last + $adjustment;
				$adjustment = ceil( $adjustment / 2 );
			}
		}

		// Loop through rows
		while ( $row < $rows ):

			// Loop through columns
			for ( $col = 0; $col < $rounds; $col++ ):

				// Get measurements from reference
				$measurements = sp_array_value( $reference, $col, array() );
				$margin = sp_array_value( $measurements, 'margin', 0 );
				$spacing = sp_array_value( $measurements, 'spacing', 0 );
				$height = sp_array_value( $measurements, 'height', 0 );

				// Get event index
				$index = ( pow( 2, $rounds ) - pow( 2, ( $rounds - $col ) ) + floor( $counter[ $col ] / 3 ) );

				// Check if event is hidden
				$hidden = sp_array_value( sp_array_value( $raw, $index, array() ), 'hidden', 0 );
				
				// Initialize forced
				$forced = 0;
				
				// Hide odd events in double elimination
				if ( 'losers' === $type ) {
					if ( 0 === $col && 0 === $index % 2 ) {
						$hidden = 1;
						$forced = 1;
						$raw[ $index ]['hidden'] = 1;
					}
				} elseif ( 'finals' === $type ) {
					if ( ! in_array( $index, $sequence ) ) {
						$hidden = 1;
						$forced = 1;
						$raw[ $index ]['hidden'] = 1;
					}
				}
				
				// Get selected event id
				$event = sp_array_value( $events, $index, 0 );

				// Get teams
				$teams = array();
				if ( $event ) {
					$post_status = get_post_status( $event );
					if ( is_array( $event ) ) {
						$teams = $event;
						$event = null;
					} else {
						if ( is_string( $post_status ) && 'trash' !== $post_status ) {
							$teams = get_post_meta( $event, 'sp_team', array() );
						} else {
							$event = null;
						}
					}
				}

				// Determine if we are on the other side
				if ( 'center' == $layout && $row >= $maxrows ):
					$flip = true;
				else:
					$flip = false;
				endif;

				if ( $flip ):
					if ( $col + 1 == $rounds ):
						continue;
					endif;

					$cellrow = $row - $maxrows - 1;
					$cellcol = 2 * $rounds - $col - 2;
				else:
					$cellrow = $row;
					$cellcol = $col;
				endif;

				// Add event, team, or spacer
				if ( $row % ( 6 * pow( 2, $col ) ) === $margin + 1 ):
					$cell = array(
						'type' => 'event',
						'rows' => $height,
						'index' => $index,
						'id' => $event,
						'hidden' => $hidden,
						'forced' => $forced,
					);

					if ( $rounds - 1 == $col ):
						$cell['class'] = 'sp-event-final';
					elseif ( $flip ):
						$cell['class'] = 'sp-event-flip';
					endif;

					$data[ $cellrow ][ $cellcol ] = $cell;

					$counter[ $col ] ++;
				elseif ( $row % ( 6 * pow( 2, $col ) ) === $margin || $row % ( 6 * pow( 2, $col ) ) === $margin + $height + 1 ):
					$ti = $oi = ( $row % ( 6 * pow( 2, $col ) ) === $margin ? 0 : 1 );
					
					// Reverse teams if needed
					if ( $reverse_teams ) {
						$oi = $ti;
						$ti = ( $ti ? 0 : 1 );
					}
					
					$select = 0;
					$team = sp_array_value( $teams, $ti, 0 );
					if ( ! $team ) {
						$select = 1;
						$team = sp_array_value( sp_array_value( sp_array_value( $raw, $index, array() ), 'teams', array() ), $ti, 0 );
					}

					$cell = array(
						'type' => 'team',
						'rows' => 1,
						'index' => $index,
						'class' => ( $ti ? 'sp-away-team' : 'sp-home-team' ),
						'id' => $team,
						'hidden' => $hidden,
						'forced' => $forced,
						'pos' => $oi,
						'select' => $select,
					);

					if ( 'center' == $layout && $rounds - 1 == $col ):
						$cell['class'] = ( $ti ? 'sp-team-flip sp-team-final' : 'sp-team-final' );
					elseif ( $flip ):
						$cell['class'] = 'sp-team-flip';
					endif;

					$data[ $cellrow ][ $cellcol ] = $cell;

					$counter[ $col ] ++;
				elseif ( $cellrow === 0 ):
					$data[ $cellrow ][ $cellcol ] = array(
						'type' => 'spacer',
						'rows' => $margin,
					);
				elseif ( $row % ( 6 * pow( 2, $col ) ) === $margin + $height + 2 ):
					$data[ $cellrow ][ $cellcol ] = array(
						'type' => 'spacer',
						'rows' => min( $spacing, $maxrows - $cellrow ),
					);
				endif;

			endfor;

			$row++;

		endwhile;

		if ( $admin ) {
			return array( $labels, $data, $columns, $maxrows, $rounds, $raw );
		} else {
			return $data;
		}
	}
}
