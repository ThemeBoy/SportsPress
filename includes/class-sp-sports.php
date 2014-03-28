<?php
/**
 * SportsPress sports
 *
 * The SportsPress sports class stores preset sport data.
 *
 * @class 		SP_Sports
 * @version		0.7
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Sports {

	/** @var array Array of sports */
	private $data;

	/**
	 * Constructor for the sports class - defines all preset sports.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = sp_get_sport_options();
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
