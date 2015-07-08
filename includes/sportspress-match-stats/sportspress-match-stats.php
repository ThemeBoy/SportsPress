<?php
/*
Plugin Name: SportsPress: Match Stats
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

		// Include required files
		$this->includes();

		add_filter( 'gettext', array( $this, 'gettext' ), 20, 3 );
		add_action( 'sportspress_single_event_content', array( $this, 'template' ), 60 );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter('body_class', array( $this, 'body_class' ) );
		add_filter( 'sportspress_event_template_options', array( $this, 'add_options' ) );
		//add_filter( 'sportspress_staff_options', array( $this, 'add_staff_options' ) );
		//add_filter( 'sportspress_player_details', array( $this, 'add_player_details' ), 20, 2 );
		//add_filter( 'sportspress_staff_details', array( $this, 'add_staff_details' ), 20, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
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
	 * Include required files.
	*/
	private function includes() {
	}

	/** 
	 * Text filter.
	 */
	public function gettext( $translated_text, $untranslated_text, $domain ) {
		if ( ! is_admin() ) return $translated_text;

		global $typenow;
		
		if ( 'default' == $domain && in_array( $typenow, array( 'sp_player', 'sp_staff' ) ) ):
			switch ( $untranslated_text ):
				case 'Scheduled for: <b>%1$s</b>':
				case 'Published on: <b>%1$s</b>':
				case 'Schedule for: <b>%1$s</b>':
				case 'Publish on: <b>%1$s</b>':
					return __( 'Birthday: <b>%1$s</b>', 'sportspress' );
				case 'Publish <b>immediately</b>':
					return __( 'Birthday', 'sportspress' );
				case 'M j, Y @ G:i':
					return 'M j, Y';
				case '%1$s %2$s, %3$s @ %4$s : %5$s':
					$hour = '<input type="hidden" id="hh" name="hh" value="00" readonly />';
					$minute = '<input type="hidden" id="mn" name="mn" value="00" readonly />';
					return '%1$s %2$s, %3$s' . $hour . $minute;
			endswitch;
		endif;
		
		return $translated_text;
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
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_MATCH_STATS_URL ) . 'css/sportspress-event-statistics.css',
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
			$classes[] = 'sp-inline-statistics';
		}
		return $classes;
	}


	/**
	 * Add data to player details template.
	 *
	 * @return array
	 */
	public function add_player_details( $data, $post_id ) {
		if ( 'yes' == get_option( 'sportspress_player_show_birthday', 'no' ) ) {
			$data[ __( 'Birthday', 'sportspress' ) ] = get_the_date( get_option( 'date_format' ), $post_id );
		}

		if ( 'yes' == get_option( 'sportspress_player_show_age', 'no' ) ) {
			$data[ __( 'Age', 'sportspress' ) ] = $this->get_age( get_the_date( 'm-d-Y' ) );
		}

		return $data;
	}

	/**
	 * Add data to staff details template.
	 *
	 * @return array
	 */
	public function add_staff_details( $data, $post_id ) {
		if ( 'yes' == get_option( 'sportspress_staff_show_birthday', 'no' ) ) {
			$data[ __( 'Birthday', 'sportspress' ) ] = get_the_date( get_option( 'date_format' ), $post_id );
		}

		if ( 'yes' == get_option( 'sportspress_staff_show_age', 'no' ) ) {
			$data[ __( 'Age', 'sportspress' ) ] = $this->get_age( get_the_date( 'm-d-Y' ) );
		}

		return $data;
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_style( 'sportspress-birthdays-admin', SP_MATCH_STATS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}

	/**
	 * Get age from date.
 	 * Adapted from http://stackoverflow.com/questions/3776682/php-calculate-age.
	 *
	 * @return int
	 */
	public static function get_age( $date ) {
		$date = explode( '-', $date );
		$age = ( date( 'md', date( 'U', mktime( 0, 0, 0, $date[0], $date[1], $date[2] ) ) ) > date('md')
			? ( ( date( 'Y' ) - $date[2] ) - 1 )
			: ( date( 'Y' ) - $date[2] ) );
		return $age;
	}
}

endif;

if ( get_option( 'sportspress_load_match_stats_module', 'yes' ) == 'yes' ) {
	new SportsPress_Match_Stats();
}
