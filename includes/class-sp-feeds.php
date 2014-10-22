<?php
/**
 * SportsPress Feeds Class
 *
 * @class 		SP_Feeds
 * @version		1.4
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Feeds {

	/** @var array Array of feeds */
	private $data;

	/**
	 * Constructor for the feeds class - defines all preset feeds.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$data = array(
			'calendar' => array(
				'ical' => __( 'iCal', 'sportspress' ),
			),
		);

		$this->data = apply_filters( 'sportspress_feeds', $data );

		foreach ( $data as $type => $feeds ) {
			foreach ( $feeds as $slug => $name ) {
				$this->feed = $slug;
				add_feed( 'sp-' . $type . '-' . $slug, array( $this, 'load_' . $type . '_' . $slug . '_feed' ) );
			}
		}
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}

	public static function load_calendar_ical_feed() {
	    $feed_template = SP()->plugin_path() . '/feeds/ical.php';
	    load_template( $feed_template );
	}
}

