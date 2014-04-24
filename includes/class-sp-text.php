<?php
/**
 * SportsPress text
 *
 * The SportsPress text class stores editable strings.
 *
 * @class 		SP_Text
 * @version		0.8
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Text {

	/** @var array Array of text */
	public $data;

	/**
	 * Constructor for the text class - defines all editable strings.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = sp_get_text_options();
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}

	public function string( $string ){
		if ( is_admin() )
			return $string;
		
		$key = str_replace( '-', '_', sanitize_title( $string ) );

		if ( array_key_exists( $key, $this->data ) ):
			$string = get_option( 'sportspress_' . $key . '_text' );
			return ( empty( $string ) ? $this->data[ $key ] : $string );
		else:
			return $string;
		endif;
	}
}
