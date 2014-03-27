<?php
/**
 * SportsPress text
 *
 * The SportsPress text class stores editable strings.
 *
 * @class 		SP_Text
 * @version		0.7
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Text {

	/** @var array Array of text */
	public $data;

	/**
	 * Constructor for the text class - defines all editable strings.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = apply_filters( 'sportspress_text', array(
			'general' => array(
				'league' => __( 'League', 'sportspress' ),
				'season' => __( 'Season', 'sportspress' ),
			),
			'event' => array(
				'event' => __( 'Event', 'sportspress' ),
				'date' => __( 'Date', 'sportspress' ),
				'time' => __( 'Time', 'sportspress' ),
				'results' => __( 'Results', 'sportspress' ),
				'team' => __( 'Team', 'sportspress' ),
				'teams' => __( 'Teams', 'sportspress' ),
				'details' => __( 'Details', 'sportspress' ),
				'venue' => __( 'Venue', 'sportspress' ),
				'player' => __( 'Player', 'sportspress' ),
				'substitutes' => __( 'Substitutes', 'sportspress' ),
				'total' => __( 'Total', 'sportspress' ),
				'article' => __( 'Article', 'sportspress' ),
				'preview' => __( 'Preview', 'sportspress' ),
				'recap' => __( 'Recap', 'sportspress' ),
				'view_all_events' => __( 'View all events', 'sportspress' ),
			),
			'team' => array(
				'team' => __( 'Team', 'sportspress' ),
				'teams' => __( 'Teams', 'sportspress' ),
				'pos' => __( 'Pos', 'sportspress' ),
				'view_full_table' => __( 'View full table', 'sportspress' ),
			),
			'player' => array(
				'player' => __( 'Player', 'sportspress' ),
				'position' => __( 'Position', 'sportspress' ),
				'nationality' => __( 'Nationality', 'sportspress' ),
				'current_team' => __( 'Current Team', 'sportspress' ),
				'past_teams' => __( 'Past Teams', 'sportspress' ),
				'rank' => __( 'Rank', 'sportspress' ),
				'view_all_players' => __( 'View all players', 'sportspress' ),
			),
			'staff' => array(
				'staff' => __( 'Staff', 'sportspress' ),
			),
		));
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}

	public function string( $key, $context = null ){
		$key = str_replace( '-', '_', sanitize_title( $key ) );

		if ( $context == null )
			$context = 'general';

		if ( array_key_exists( $context, $this->data ) && array_key_exists( $key, $this->data[ $context ] ) ):
			$string = get_option( 'sportspress_' . ( $context == 'general' ? '' : $context . '_' ) . $key . '_text' );
			return ( empty( $string ) ? $this->data[ $context ][ $key ] : $string );
		else:
			return $key;
		endif;
	}
}
