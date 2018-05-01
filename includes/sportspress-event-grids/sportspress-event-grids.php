<?php
/*
Plugin Name: SportsPress Match Grids
Plugin URI: http://tboy.co/pro
Description: Display fixtures and results between teams in a grid layout.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Grids' ) ) :

/**
 * Main SportsPress Event Grids Class
 *
 * @class SportsPress_Event_Grids
 * @version	2.6
 */
class SportsPress_Event_Grids {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required ajax files
		if ( defined( 'DOING_AJAX' ) ) {
			$this->ajax_includes();
		}

		// Hooks
		add_filter( 'sportspress_formats', array( $this, 'add_formats' ) );
		add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_event_settings', array( $this, 'add_settings' ) );
		add_filter( 'sportspress_locate_template', array( $this, 'locate_template' ), 20, 3 );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_shortcode( 'event_grid', array( $this, 'shortcode' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_tinymce_strings', array( $this, 'add_tinymce_strings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_MATCH_GRIDS_VERSION' ) )
			define( 'SP_MATCH_GRIDS_VERSION', '2.6' );

		if ( !defined( 'SP_MATCH_GRIDS_URL' ) )
			define( 'SP_MATCH_GRIDS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_MATCH_GRIDS_DIR' ) )
			define( 'SP_MATCH_GRIDS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required ajax files.
	 */
	public function ajax_includes() {
		include_once( 'includes/class-sp-grid-ajax.php' );
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Match Grids', 'sportspress' ), 'type' => 'title', 'id' => 'event_grid_options' ),
			),

			apply_filters( 'sportspress_event_grid_options', array(
				array(
					'title' 	=> __( 'Date Format', 'sportspress' ),
					'id' 		=> 'sportspress_event_grid_date_format',
					'class' 	=> 'small-text',
					'default'	=> 'M j',
					'type' 		=> 'text',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_event_grid_show_logos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_grid_options' ),
			)
		);
		return $settings;
	}

	/** 
	 * Locate template.
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		if ( 'event-grid.php' !== $template_name )
			return $template;
		
		$default_path = trailingslashit( SP_MATCH_GRIDS_DIR ) . 'templates/';

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
		$formats['calendar']['grid'] = __( 'Grid', 'sportspress' );

		return $formats;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-event-grids'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_MATCH_GRIDS_URL ) . 'css/sportspress-event-grids.css',
			'deps'    => 'sportspress-general',
			'version' => SP_MATCH_GRIDS_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		include_once( 'includes/class-sp-widget-event-grid.php' );
	}

	/**
	 * Add event grid shortcode.
	 *
	 * @param array $atts
	 */
	public static function shortcode( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();

		echo '<div class="sportspress">';
		sp_get_template( 'event-event-grid.php', $atts, '', trailingslashit( SP_MATCH_GRIDS_DIR ) . 'templates/' );
		echo '</div>';

		return ob_get_clean();
	}

	/**
	 * Add shortcodes to TinyMCE
	 */
	public static function add_shortcodes( $shortcodes ) {
		$shortcodes['event'][] = 'grid';
		return $shortcodes;
	}

	/**
	 * Add strings to TinyMCE
	 */
	public static function add_tinymce_strings( $strings ) {
		$strings['grid'] = __( 'Grid', 'sportspress' );
		return $strings;
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'sportspress-event-grids-admin', SP_MATCH_GRIDS_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}
}

endif;

if ( get_option( 'sportspress_load_event_grids_module', 'yes' ) == 'yes' ) {
	new SportsPress_Event_Grids();
}
