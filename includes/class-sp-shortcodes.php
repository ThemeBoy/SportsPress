<?php
/**
 * SP_Shortcodes class.
 *
 * @class 		SP_Shortcodes
 * @version		2.6.9
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
			'event_results'     => __CLASS__ . '::event_results',
			'event_details'     => __CLASS__ . '::event_details',
			'event_performance' => __CLASS__ . '::event_performance',
			'event_venue' 		=> __CLASS__ . '::event_venue',
			'event_officials' 	=> __CLASS__ . '::event_officials',
			'event_teams' 		=> __CLASS__ . '::event_teams',
			'event_full' 		=> __CLASS__ . '::event_full',
			'countdown'         => __CLASS__ . '::countdown',
			'player_details'    => __CLASS__ . '::player_details',
			'player_statistics' => __CLASS__ . '::player_statistics',
			'staff'             => __CLASS__ . '::staff',
			'staff_profile'     => __CLASS__ . '::staff_profile',
			'event_calendar'    => __CLASS__ . '::event_calendar',
			'event_list'        => __CLASS__ . '::event_list',
			'event_blocks'      => __CLASS__ . '::event_blocks',
			'league_table'      => __CLASS__ . '::league_table',
			'team_standings'    => __CLASS__ . '::league_table',
			'team_gallery'      => __CLASS__ . '::team_gallery',
			'player_list'       => __CLASS__ . '::player_list',
			'player_gallery'    => __CLASS__ . '::player_gallery',
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
		$wrapper = apply_filters( 'sportspress_shortcode_wrapper', $wrapper, $function, $atts );

		ob_start();

		$before 	= empty( $wrapper['before'] ) ? '<div class="' . esc_attr( $wrapper['class'] ) . '">' : $wrapper['before'];
		$after 		= empty( $wrapper['after'] ) ? '</div>' : $wrapper['after'];

		echo $before;
		call_user_func( $function, $atts );
		echo $after;

		return ob_get_clean();
	}

	/**
	 * Event results shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_results( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Results', 'output' ), $atts );
	}

	/**
	 * Event details shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_details( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Details', 'output' ), $atts );
	}

	/**
	 * Event performance shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_performance( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Performance', 'output' ), $atts );
	}
	
	/**
	 * Event venue shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_venue( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Venue', 'output' ), $atts );
	}
	
	/**
	 * Event officials shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_officials( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Officials', 'output' ), $atts );
	}
	
	/**
	 * Event teams shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_teams( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Teams', 'output' ), $atts );
	}
	
	/**
	 * Event full info shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_full( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Full', 'output' ), $atts );
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
	 * Event blocks shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function event_blocks( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Event_Blocks', 'output' ), $atts );
	}

	/**
	 * League table (team standings) shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function league_table( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_League_Table', 'output' ), $atts );
	}

	/**
	 * Team gallery shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function team_gallery( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Team_Gallery', 'output' ), $atts );
	}

	/**
	 * Player details shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player_details( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player_Details', 'output' ), $atts );
	}

	/**
	 * Player statistics shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player_statistics( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player_Statistics', 'output' ), $atts );
	}

	/**
	 * Player performance shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function player_performance( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Player_Performance', 'output' ), $atts );
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

	/**
	 * Staff profile shortcode.
	 *
	 * @access public
	 * @param mixed $atts
	 * @return string
	 */
	public static function staff_profile( $atts ) {
		return self::shortcode_wrapper( array( 'SP_Shortcode_Staff_Profile', 'output' ), $atts );
	}
}
