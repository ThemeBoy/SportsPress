<?php
/**
 * SP_Shortcodes class.
 *
 * @class 		SP_Shortcodes
 * @version		0.7
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Shortcodes {

	/**
	 * Init shortcodes
	 */
	public static function init() {
		// Define shortcodes
		$shortcodes = array(
			'event'				=> __CLASS__ . '::event',
			'countdown'      	=> __CLASS__ . '::countdown',
			'event_list'     	=> __CLASS__ . '::event_list',
			'event_calendar' 	=> __CLASS__ . '::event_calendar',
//			'team'      		=> __CLASS__ . '::team',
			'league_table'   	=> __CLASS__ . '::league_table',
//			'player'      		=> __CLASS__ . '::player',
			'player_list'    	=> __CLASS__ . '::player_list',
			'player_gallery' 	=> __CLASS__ . '::player_gallery',
//			'staff'      		=> __CLASS__ . '::staff',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( $shortcode, $function );
		}
	}

	/**
	 * Shortcode Wrapper
	 *
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public static function shortcode_wrapper(
		$function,
		$atts    = array(),
		$wrapper = array(
			'class'  => 'sportspress',
			'before' => null,
			'after'  => null
		)
	) {
		ob_start();

		$before 	= empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		$after 		= empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		echo $before;
		call_user_func( $function, $atts );
		echo $after;

		return ob_get_clean();
	}

	/**
	 * Event shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event', 'output' ), $atts );
	}

	/**
	 * Countdown shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function countdown( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Countdown', 'output' ), $atts );
	}

	/**
	 * Event calendar shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_calendar( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Calendar', 'output' ), $atts );
	}

	/**
	 * Event list shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_list( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_List', 'output' ), $atts );
	}

	/**
	 * Team shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function team( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Team', 'output' ), $atts );
	}

	/**
	 * League table shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function league_table( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_League_Table', 'output' ), $atts );
	}

	/**
	 * Player shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player', 'output' ), $atts );
	}

	/**
	 * Player list shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player_list( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player_List', 'output' ), $atts );
	}

	/**
	 * Player gallery shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player_gallery( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player_Gallery', 'output' ), $atts );
	}

	/**
	 * Staff shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function staff( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Staff', 'output' ), $atts );
	}
}
