<?php
/**
 * SportsPress formats
 *
 * The SportsPress formats class stores preset sport data.
 *
 * @class 		SP_Formats
 * @version		0.7
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Formats {

	/** @var array Array of formats */
	public $formats;

	/**
	 * Constructor for the formats class - defines all preset formats.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->formats = apply_filters( 'sportspress_formats', array(
			'event' => array(
				'league' => __( 'League', 'sportspress' ),
				'friendly' => __( 'Friendly', 'sportspress' ),
			),
			'calendar' => array(
				'calendar' => __( 'Calendar', 'sportspress' ),
				'list' => __( 'List', 'sportspress' ),
			),
			'list' => array(
				'list' => __( 'List', 'sportspress' ),
				'gallery' => __( 'Gallery', 'sportspress' ),
			),
		));
	}
}
