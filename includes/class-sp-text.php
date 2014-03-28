<?php
/**
 * SportsPress text
 *
 * The SportsPress text class stores editable strings.
 *
 * @class 		SP_Text
 * @version		0.7
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

	public function string( $string, $context = null ){
		if ( is_admin() )
			return $string;
		
		$key = str_replace( '-', '_', sanitize_title( $string ) );

		if ( $context == null )
			$context = 'general';

		if ( array_key_exists( $context, $this->data ) && array_key_exists( $key, $this->data[ $context ] ) ):
			$string = get_option( 'sportspress_' . ( $context == 'general' ? '' : $context . '_' ) . $key . '_text' );
			return ( empty( $string ) ? $this->data[ $context ][ $key ] : $string );
		else:
			return $string;
		endif;
	}
}
