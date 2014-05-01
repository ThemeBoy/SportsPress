<?php
/**
 * SportsPress Admin Sports Class.
 *
 * The SportsPress admin sports class stores preset sport data.
 *
 * @class 		SP_Admin_Sports
 * @version		0.8
 * @package		SportsPress/Admin
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Admin_Sports {
	private static $presets = array();

	/**
	 * Include the preset classes
	 */
	public static function get_presets() {
		if ( empty( self::$presets ) ) {
			$presets = array();

			include( 'presets/class-sp-preset-sport.php' );

			$dir = scandir( SP()->plugin_path() . '/presets' );
			$files = array();
			if ( $dir ) {
				foreach ( $dir as $key => $value ) {
					if ( ! in_array( $value, array( ".",".." ) ) ) {
						$files[] = $value;
					}
				}
			}
			foreach( $files as $file ) {
				$json_data = file_get_contents( SP()->plugin_path() . '/presets/' . $file );
				$data = json_decode( $json_data, true );
				pr( $data );
			}

			//$presets[] = include( 'presets/class-sp-preset-soccer.php' );
			//$presets[] = include( 'presets/class-sp-preset-baseball.php' );SP_TEMPLATE_PATH

			self::$presets = apply_filters( 'sportspress_get_presets', $presets );
		}
		return self::$presets;
	}

	public static function get_preset_options() {
		$presets = self::get_presets();
	    $options = apply_filters( 'sportspress_sport_presets_array', array() );
		return $options;
	}

	/** @var array Array of sports */
	private $data;

	/**
	 * Constructor for the sports class - defines all preset sports.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = sp_get_sport_presets();
	}

	public function __get( $key ) {
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
