<?php
/*
Plugin Name: SportsPress Styles
Plugin URI: http://themeboy.com/
Description: Add frontend styles to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Styles' ) ) :

/**
 * Main SportsPress Styles Class
 *
 * @class SportsPress_Styles
 * @version	2.7
 */
class SportsPress_Styles {

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
		if ( !defined( 'SP_STYLES_VERSION' ) )
			define( 'SP_STYLES_VERSION', '2.7' );

		if ( !defined( 'SP_STYLES_URL' ) )
			define( 'SP_STYLES_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_STYLES_DIR' ) )
			define( 'SP_STYLES_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add option.
	*/
	public static function add_option( $options = array() ) {
		if ( current_theme_supports( 'sportspress' ) && ! current_theme_supports( 'sportspress-styles' ) ) return $options;

		array_unshift( $options, array(
			'title'     => __( 'Frontend Styles', 'sportspress' ),
			'desc' 		=> __( 'Enable', 'sportspress' ),
			'id' 		=> 'sportspress_styles',
			'default'	=> 'yes',
			'type' 		=> 'checkbox',
		) );

		return $options;
	}

	/**
	 * Add stylesheet.
	*/
	public static function add_styles( $styles = array() ) {
		if ( current_theme_supports( 'sportspress' ) && ! current_theme_supports( 'sportspress-styles' ) ) return $styles;
		if ( 'no' === get_option( 'sportspress_styles', 'yes' ) ) return $styles;
		
		$styles['sportspress-roboto'] = array(
			'src'     => '//fonts.googleapis.com/css?family=Roboto:400,500&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese',
			'deps'    => '',
			'version' => SP_STYLES_VERSION,
			'media'   => 'all'
		);

		$styles['sportspress-style'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP()->plugin_url() ) . '/assets/css/sportspress-style.css',
			'deps'    => '',
			'version' => SP_STYLES_VERSION,
			'media'   => 'all'
		);

		if ( is_rtl() ) {
			$styles['sportspress-style-rtl'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP()->plugin_url() ) . '/assets/css/sportspress-style-rtl.css',
				'deps'    => 'sportspress-style',
				'version' => SP_STYLES_VERSION,
				'media'   => 'all'
			);
		} else {
			$styles['sportspress-style-ltr'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP()->plugin_url() ) . '/assets/css/sportspress-style-ltr.css',
				'deps'    => 'sportspress-style',
				'version' => SP_STYLES_VERSION,
				'media'   => 'all'
			);
		}

		return $styles;
	}

	/**
	 * Output custom CSS.
	 */
	public function custom_css( $colors = array() ) {

		if ( current_theme_supports( 'sportspress' ) && ! current_theme_supports( 'sportspress-styles' ) ) {
			return;
		}

		if ( 'no' === get_option( 'sportspress_styles', 'yes' ) ) {
			return;
		}

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
		$colors['text_muted'] = 'rgba(' . implode( ',', $rgb ) . ',0.5)';

		// Primary
		echo '.sp-data-table th,.sp-template-countdown .sp-event-venue,.sp-template-countdown .sp-event-league,.sp-template-gallery .gallery-caption{background:' . $colors['primary'] . ' !important}';
		echo '.sp-data-table th,.sp-template-countdown .sp-event-venue,.sp-template-countdown .sp-event-league,.sp-template-gallery .gallery-caption{border-color:' . $colors['primary_border'] . ' !important}';
		
		// Background
		echo '.sp-table-caption,.sp-data-table,.sp-data-table tfoot,.sp-template .sp-view-all-link,.sp-template-gallery .sp-gallery-group-name,.sp-template-gallery .sp-gallery-wrapper,.sp-template-countdown .sp-event-name,.sp-countdown time,.sp-template-details dl,.sp-event-statistics .sp-statistic-bar,.sp-tournament-bracket .sp-team-name,.sp-profile-selector{background:' . $colors['background'] . ' !important}';
		echo '.sp-table-caption,.sp-data-table,.sp-data-table td,.sp-template .sp-view-all-link,.sp-template-gallery .sp-gallery-group-name,.sp-template-gallery .sp-gallery-wrapper,.sp-template-countdown .sp-event-name,.sp-countdown time,.sp-countdown span,.sp-template-details dl,.sp-event-statistics .sp-statistic-bar,.sp-tournament-bracket thead th,.sp-tournament-bracket .sp-team-name,.sp-tournament-bracket .sp-event,.sp-profile-selector{border-color:' . $colors['background_border'] . ' !important}';
		echo '.sp-tournament-bracket .sp-team .sp-team-name:before{border-left-color:' . $colors['background_border'] . ' !important;border-right-color:' . $colors['background_border'] . ' !important}';
		echo '.sp-data-table .sp-highlight,.sp-data-table .highlighted td,.sp-template-scoreboard td:hover{background:' . $colors['background_highlight'] . ' !important}';

		// Text
		echo '.sp-template *,.sp-data-table *,.sp-table-caption,.sp-data-table tfoot a:hover,.sp-template .sp-view-all-link a:hover,.sp-template-gallery .sp-gallery-group-name,.sp-template-details dd,.sp-template-event-logos .sp-team-result,.sp-template-event-blocks .sp-event-results,.sp-template-scoreboard a,.sp-template-scoreboard a:hover,.sp-tournament-bracket,.sp-tournament-bracket .sp-event .sp-event-title:hover,.sp-tournament-bracket .sp-event .sp-event-title:hover *{color:' . $colors['text'] . ' !important}';
		echo '.sp-template .sp-view-all-link a,.sp-countdown span small,.sp-template-event-calendar tfoot a,.sp-template-event-blocks .sp-event-date,.sp-template-details dt,.sp-template-scoreboard .sp-scoreboard-date,.sp-tournament-bracket th,.sp-tournament-bracket .sp-event .sp-event-title,.sp-template-scoreboard .sp-scoreboard-date,.sp-tournament-bracket .sp-event .sp-event-title *{color:' . $colors['text_muted'] . ' !important}';

		// Heading
		echo '.sp-data-table th,.sp-template-countdown .sp-event-venue,.sp-template-countdown .sp-event-league,.sp-template-gallery .gallery-item a,.sp-template-gallery .gallery-caption,.sp-template-scoreboard .sp-scoreboard-nav,.sp-tournament-bracket .sp-team-name:hover,.sp-tournament-bracket thead th,.sp-tournament-bracket .sp-heading{color:' . $colors['heading'] . ' !important}';

		// Link
		echo '.sp-template a,.sp-data-table a,.sp-tab-menu-item-active a, .sp-tab-menu-item-active a:hover,.sp-template .sp-message{color:' . $colors['link'] . ' !important}';
		echo '.sp-template-gallery .gallery-caption strong,.sp-tournament-bracket .sp-team-name:hover,.sp-template-scoreboard .sp-scoreboard-nav,.sp-tournament-bracket .sp-heading{background:' . $colors['link'] . ' !important}';
		echo '.sp-tournament-bracket .sp-team-name:hover,.sp-tournament-bracket .sp-heading,.sp-tab-menu-item-active a, .sp-tab-menu-item-active a:hover,.sp-template .sp-message{border-color:' . $colors['link'] . ' !important}';
	}
}

endif;

new SportsPress_Styles();
