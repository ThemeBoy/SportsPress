<?php
/*
Plugin Name: SportsPress Scoreboards
Plugin URI: http://tboy.co/pro
Description: Adds a scoreboard layout to SportsPress event calendars.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Scoreboards' ) ) :

/**
 * Main SportsPress Scoreboards Class
 *
 * @class SportsPress_Scoreboards
 * @version	2.0
 */
class SportsPress_Scoreboards {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		//$this->includes();

		// Hooks
		add_filter( 'sportspress_formats', array( $this, 'add_formats' ) );
	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
	    add_filter( 'sportspress_locate_template', array( $this, 'locate_template' ), 20, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_frontend_css', array( $this, 'frontend_css' ) );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_SCOREBOARDS_VERSION' ) )
			define( 'SP_SCOREBOARDS_VERSION', '2.0' );

		if ( !defined( 'SP_SCOREBOARDS_URL' ) )
			define( 'SP_SCOREBOARDS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SCOREBOARDS_DIR' ) )
			define( 'SP_SCOREBOARDS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
		if ( ! is_admin() || defined( 'DOING_AJAX' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		include_once( 'includes/class-sp-scoreboard-template-loader.php' );
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Scoreboard', 'sportspress' ), 'type' => 'title', 'id' => 'scoreboard_options' ),
			),

			apply_filters( 'sportspress_scoreboard_options', array(
				array(
					'title'     => __( 'Display', 'sportspress' ),
					'desc' 		=> __( 'Date', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_date',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'	=> 'start',
				),

				array(
					'desc' 		=> __( 'Time', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_time',
					'default'	=> 'yes',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Competition', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_league',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Season', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_season',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> '',
				),

				array(
					'desc' 		=> __( 'Venue', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_venue',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
					'checkboxgroup'		=> 'end',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_logos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title' 	=> __( 'Limit', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_limit',
					'class' 	=> 'small-text',
					'default'	=> '8',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),

				array(
					'title' 	=> __( 'Width', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_width',
					'class' 	=> 'small-text',
					'default'	=> '120',
					'desc' 		=> 'px',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 50,
						'step' 	=> 1
					),
				),

				array(
					'title' 	=> __( 'Pagination', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_step',
					'class' 	=> 'small-text',
					'default'	=> '2',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 1,
						'step' 	=> 1
					),
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'scoreboard_options' ),
			)
		);
		return $settings;
	}

	/** 
	 * Locate template.
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		if ( 'event-scoreboard.php' !== $template_name )
			return $template;
		
		$default_path = trailingslashit( SP_SCOREBOARDS_DIR ) . 'templates/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get default template
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		return $template;
	}


	/** 
	 * Add formats.
	 */
	public function add_formats( $formats ) {
		$formats['calendar']['scoreboard'] = __( 'Scoreboard', 'sportspress' );

		return $formats;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-scoreboards'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SCOREBOARDS_URL ) . 'css/sportspress-scoreboards.css',
			'deps'    => 'sportspress-general',
			'version' => SP_SCOREBOARDS_VERSION,
			'media'   => 'all'
		);

		if ( is_rtl() ) {
			$styles['sportspress-scoreboards-rtl'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SCOREBOARDS_URL ) . 'css/sportspress-scoreboards-rtl.css',
				'deps'    => 'sportspress-scoreboards',
				'version' => SP_SCOREBOARDS_VERSION,
				'media'   => 'all'
			);
		}
		return $styles;
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		wp_enqueue_script( 'sportspress-scoreboards', SP_SCOREBOARDS_URL .'js/sportspress-scoreboards.js', array( 'jquery' ), time(), true );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'sportspress-scoreboards-admin', SP_SCOREBOARDS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}

	/**
	 * Frontend CSS
	 */
	public static function frontend_css( $colors ) {
		if ( current_theme_supports( 'sportspress' ) )
			return;

		if ( isset( $colors['highlight'] ) ) {
			echo '.sp-tournament-bracket .sp-event{border-color:' . $colors['highlight'] . ' !important}';
			echo '.sp-tournament-bracket .sp-team .sp-team-name:before{border-left-color:' . $colors['highlight'] . ' !important}';
		}
		if ( isset( $colors['text'] ) ) {
			echo '.sp-tournament-bracket .sp-event .sp-event-main, .sp-tournament-bracket .sp-team .sp-team-name{color:' . $colors['text'] . ' !important}';
		}
		if ( isset( $colors['heading'] ) ) {
			echo '.sp-tournament-bracket .sp-team .sp-team-name.sp-heading{color:' . $colors['heading'] . ' !important}';
		}
	}
}

endif;

if ( get_option( 'sportspress_load_scoreboards_module', 'yes' ) == 'yes' ) {
	new SportsPress_Scoreboards();
}
