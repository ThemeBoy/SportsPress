<?php
/*
Plugin Name: SportsPress Match Stats
Plugin URI: http://themeboy.com/
Description: Display head-to-head team comparison charts in events.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 1.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Match_Stats' ) ) :

/**
 * Main SportsPress Match Stats Class
 *
 * @class SportsPress_Match_Stats
 * @version	1.6
 */
class SportsPress_Match_Stats {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'sportspress_event_performance', array( $this, 'template' ) );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'sportspress_event_template_options', array( $this, 'add_options' ) );
		add_action( 'sportspress_frontend_css', array( $this, 'frontend_css' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_MATCH_STATS_VERSION' ) )
			define( 'SP_MATCH_STATS_VERSION', '1.6' );

		if ( !defined( 'SP_MATCH_STATS_URL' ) )
			define( 'SP_MATCH_STATS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_MATCH_STATS_DIR' ) )
			define( 'SP_MATCH_STATS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Add options to event settings page.
	 *
	 * @return array
	 */
	public function add_options( $options = array() ) {
		$last = array_pop( $options );
		$last['checkboxgroup'] = '';
		$options[] = $last;
		$options = array_merge( $options, array(
			array(
				'desc' 		=> __( 'Match Stats', 'sportspress' ),
				'id' 		=> 'sportspress_event_show_statistics',
				'default'	=> 'yes',
				'type' 		=> 'checkbox',
				'checkboxgroup'		=> 'end',
			),
		) );

		return $options;
	}

	/**
	 * Output template.
	 *
	 * @access public
	 * @return void
	 */
	public function template() {
		sp_get_template( 'event-statistics.php', array(), '', SP_MATCH_STATS_DIR . 'templates/' );
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-event-statistics'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_MATCH_STATS_URL ) . 'css/sportspress-match-stats.css',
			'deps'    => 'sportspress-general',
			'version' => SP_MATCH_STATS_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add class to body
	 */
	public function body_class( $classes = array() ) {
		if ( 'sp_event' == get_post_type() && 'yes' == get_option( 'sportspress_event_show_statistics', 'yes' ) && 'icons' == get_option( 'sportspress_event_performance_mode', 'values' ) ) {
			$event = new SP_Event( get_the_ID() );
			$status = $event->status();
			if ( 'results' !== $status ) return $classes;
			$classes[] = 'sp-inline-statistics';
		}
		return $classes;
	}

	/**
	 * Frontend CSS
	 */
	public static function frontend_css( $colors ) {
		if ( isset( $colors['primary'] ) ) {
			echo '.sp-statistic-bar{background:' . $colors['primary'] . '}';
		}
		if ( isset( $colors['link'] ) ) {
			echo '.sp-statistic-bar-fill{background:' . $colors['link'] . '}';
		}
	}
}

endif;

if ( get_option( 'sportspress_load_match_stats_module', 'yes' ) == 'yes' ) {
	new SportsPress_Match_Stats();
}
