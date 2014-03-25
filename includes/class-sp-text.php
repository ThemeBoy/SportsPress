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
	public $text;

	/**
	 * Constructor for the text class - defines all editable strings.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$strings = array(
			__( 'Article', 'sportspress' ),
			__( 'Current Team', 'sportspress' ),
			__( 'Date', 'sportspress' ),
			__( 'Details', 'sportspress' ),
			__( 'days', 'sportspress' ),
			__( 'Event', 'sportspress' ),
			__( 'Friendly', 'sportspress' ),
			__( 'hrs', 'sportspress' ),
			__( 'League', 'sportspress' ),
			__( 'mins', 'sportspress' ),
			__( 'Nationality', 'sportspress' ),
			__( 'Past Teams', 'sportspress' ),
			__( 'Player', 'sportspress' ),
			__( 'Position', 'sportspress' ),
			__( 'Pos', 'sportspress' ),
			__( 'Preview', 'sportspress' ),
			__( 'Rank', 'sportspress' ),
			__( 'Recap', 'sportspress' ),
			__( 'Results', 'sportspress' ),
			__( 'Season', 'sportspress' ),
			__( 'secs', 'sportspress' ),
			__( 'Staff', 'sportspress' ),
			__( 'Substitute', 'sportspress' ),
			__( 'Team', 'sportspress' ),
			__( 'Teams', 'sportspress' ),
			__( 'Time', 'sportspress' ),
			__( 'Total', 'sportspress' ),
			__( 'Venue', 'sportspress' ),
			__( 'View all players', 'sportspress' ),
			__( 'View all events', 'sportspress' ),
			__( 'View full table', 'sportspress' ),
		);
		sort( $strings );
		$this->strings = apply_filters( 'sportspress_text', $strings );
	}
}
