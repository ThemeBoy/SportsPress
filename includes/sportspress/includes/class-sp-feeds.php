<?php
/**
 * SportsPress Feeds Class
 *
 * @class 		SP_Feeds
 * @version		1.5
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
				'ical' => array(
					'apple' => array(
						'name' => __( 'Apple Calendar', 'sportspress' ),
						'protocol' => 'webcal',
					),
					'outlook' => array(
						'name' => __( 'Outlook', 'sportspress' ),
						'protocol' => 'webcal',
					),
					'google' => array(
						'name' => __( 'Google', 'sportspress' ),
						'prefix' => 'http://www.google.com/calendar/render?cid=',
					),
				),
			),
		);

		$this->data = apply_filters( 'sportspress_feeds', $data );

		foreach ( $data as $post_type => $feeds ) {
			foreach ( $feeds as $slug => $formats ) {
				add_feed( 'sp-' . $slug, array( $this, $slug . '_feed' ) );
			}
		}
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}

	public static function ical_feed() {
		$feed_template = SP()->plugin_path() . '/feeds/ical.php';
	    load_template( $feed_template );
	}
}
