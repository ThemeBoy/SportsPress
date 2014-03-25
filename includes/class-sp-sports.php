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
	public $sports;

	/**
	 * Constructor for the sports class - defines all preset sports.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->sports = apply_filters( 'sportspress_sports', array(
		));
	}
}
