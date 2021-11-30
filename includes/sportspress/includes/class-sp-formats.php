<?php
/**
 * SportsPress formats
 *
 * The SportsPress formats class stores preset sport data.
 *
 * @class       SP_Formats
 * @version   2.4
 * @package     SportsPress/Classes
 * @category    Class
 * @author      ThemeBoy
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
		$this->data = apply_filters(
			'sportspress_formats',
			array(
				'event'    => array(
					'league'   => esc_attr__( 'Competitive', 'sportspress' ),
					'friendly' => esc_attr__( 'Friendly', 'sportspress' ),
				),
				'calendar' => array(
					'calendar' => esc_attr__( 'Calendar', 'sportspress' ),
					'list'     => esc_attr__( 'List', 'sportspress' ),
					'blocks'   => esc_attr__( 'Blocks', 'sportspress' ),
				),
				'table'    => array(
					'standings' => esc_attr__( 'Standings', 'sportspress' ),
					'gallery'   => esc_attr__( 'Gallery', 'sportspress' ),
				),
				'list'     => array(
					'list'    => esc_attr__( 'List', 'sportspress' ),
					'gallery' => esc_attr__( 'Gallery', 'sportspress' ),
				),
			)
		);
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ) {
		$this->data[ $key ] = $value;
	}
}
