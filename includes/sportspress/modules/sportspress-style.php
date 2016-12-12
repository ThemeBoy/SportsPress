<?php
/*
Plugin Name: SportsPress Style
Plugin URI: http://themeboy.com/
Description: Add frontend styles to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.1.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Style' ) ) :

/**
 * Main SportsPress Style Class
 *
 * @class SportsPress_Style
 * @version	2.1.7
 */
class SportsPress_Style {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Add option
		add_filter( 'sportspress_script_styling_options', array( $this, 'add_option' ) );

		// Add stylesheet
		add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ), 20 );

		// Output custom CSS
		add_action( 'sportspress_frontend_css', array( $this, 'custom_css' ), 40 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_STYLE_VERSION' ) )
			define( 'SP_STYLE_VERSION', '2.1.7' );

		if ( !defined( 'SP_STYLE_URL' ) )
			define( 'SP_STYLE_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_STYLE_DIR' ) )
			define( 'SP_STYLE_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add option.
	*/
	public static function add_option( $options = array() ) {
		if ( ! current_theme_supports( 'sportspress' ) ):
			array_unshift( $options, array(
				'title'     => __( 'Frontend Styles', 'sportspress' ),
				'desc' 		=> __( 'Enable', 'sportspress' ),
				'id' 		=> 'sportspress_styles',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
			) );
		endif;
		return $options;
	}

	/**
	 * Add stylesheet.
	*/
	public static function add_styles( $styles = array() ) {
		if ( current_theme_supports( 'sportspress' ) ) return $styles;
		if ( 'no' === get_option( 'sportspress_styles', 'yes' ) ) return $styles;

		$styles['sportspress-style'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP()->plugin_url() ) . '/assets/css/sportspress-style.css',
			'deps'    => '',
			'version' => SP_STYLE_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Output custom CSS.
	 */
	public function custom_css( $colors = array() ) {
		if ( current_theme_supports( 'sportspress' ) ) return $styles;
		if ( 'no' === get_option( 'sportspress_styles', 'yes' ) ) return $styles;

		// Defaults
		if ( empty( $colors['primary'] ) ) $colors['primary'] = '#2b353e';
		if ( empty( $colors['background'] ) ) $colors['background'] = '#f4f4f4';
		if ( empty( $colors['text'] ) ) $colors['text'] = '#222222';
		if ( empty( $colors['heading'] ) ) $colors['heading'] = '#ffffff';
		if ( empty( $colors['link'] ) ) $colors['link'] = '#00a69c';

		// Calculate primary variations
		$colors['primary_border'] = sp_hex_darker( $colors['primary'], 26, true );

		// Calculate background variations
		$colors['background_highlight'] = sp_hex_darker( $colors['background'], 6, true );
		$colors['background_border'] = sp_hex_darker( $colors['background'], 26, true );

		// Calculate text variations
		$rgb = sp_rgb_from_hex( $colors['text'] );
		$colors['text_muted'] = sp_hex_lighter( $colors['text'], 102, true );

		// Primary
		echo '.sp-data-table th,.sp-template-gallery .gallery-caption{background:' . $colors['primary'] . ' !important}';
		echo '.sp-data-table th{border-color:' . $colors['primary_border'] . ' !important}';
		
		// Background
		echo '.sp-table-caption,.sp-template .sp-view-all-link{background:' . $colors['background'] . ' !important}';
		echo '.sp-table-caption,.sp-data-table,.sp-data-table td,.sp-template .sp-view-all-link{border-color:' . $colors['background_border'] . ' !important}';
		echo '.sp-data-table .sp-highlight,.sp-data-table .highlighted td{background:' . $colors['background_highlight'] . ' !important}';

		// Text
		echo '.sp-table-caption,.sp-template .sp-view-all-link a:hover{color:' . $colors['text'] . ' !important}';
		echo '.sp-template .sp-view-all-link a{color:' . $colors['text_muted'] . ' !important}';

		// Heading
		echo '.sp-data-table th,.sp-template-gallery .gallery-caption{color:' . $colors['heading'] . ' !important}';

		// Link
		echo '.sp-template a{color:' . $colors['link'] . ' !important}';
		echo '.sp-template-gallery .gallery-caption strong{background:' . $colors['link'] . ' !important}';
	}
}

endif;

new SportsPress_Style();
