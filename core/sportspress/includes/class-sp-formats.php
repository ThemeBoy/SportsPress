<?php
/**
 * SportsPress formats
 *
 * The SportsPress formats class stores preset sport data.
 *
 * @class 		SP_Formats
 * @version     1.4
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Formats {

	/** @var array Array of formats */
	private $data;

	/**
	 * Constructor for the formats class - defines all preset formats.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = apply_filters( 'sportspress_formats', array(
			'event' => array(
				'league' => __( 'Competitive', 'sportspress' ),
				'friendly' => __( 'Friendly', 'sportspress' ),
			),
			'calendar' => array(
				'calendar' => __( 'Calendar', 'sportspress' ),
				'list' => __( 'List', 'sportspress' ),
				'blocks' => __( 'Blocks', 'sportspress' ),
			),
			'list' => array(
				'list' => __( 'List', 'sportspress' ),
				'gallery' => __( 'Gallery', 'sportspress' ),
			),
		));
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
