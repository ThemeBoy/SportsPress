<?php
/*
Plugin Name: SportsPress Results Matrix
Plugin URI: http://tboy.co/pro
Description: Display fixtures and results between teams in a grid layout.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Results_Matrix' ) ) :

/**
 * Main SportsPress Results Matrix Class
 *
 * @class SportsPress_Results_Matrix
 * @version	2.6.9
 */
class SportsPress_Results_Matrix {

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
		add_shortcode( 'event_matrix', array( $this, 'shortcode' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_tinymce_strings', array( $this, 'add_tinymce_strings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_RESULTS_MATRIX_VERSION' ) )
			define( 'SP_RESULTS_MATRIX_VERSION', '2.6.9' );

		if ( !defined( 'SP_RESULTS_MATRIX_URL' ) )
			define( 'SP_RESULTS_MATRIX_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_RESULTS_MATRIX_DIR' ) )
			define( 'SP_RESULTS_MATRIX_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required ajax files.
	 */
	public function ajax_includes() {
		include_once( 'includes/class-sp-results-matrix-ajax.php' );
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Results Matrix', 'sportspress' ), 'type' => 'title', 'id' => 'event_matrix_options' ),
			),

			apply_filters( 'sportspress_event_matrix_options', array(
				array(
					'title' 	=> __( 'Date Format', 'sportspress' ),
					'id' 		=> 'sportspress_event_matrix_date_format',
					'class' 	=> 'small-text',
					'default'	=> 'M j',
					'type' 		=> 'text',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_event_matrix_show_logos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),
			) ),

			array(
				array( 'type' => 'sectionend', 'id' => 'event_matrix_options' ),
			)
		);
		return $settings;
	}

	/** 
	 * Locate template.
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		if ( 'event-matrix.php' !== $template_name )
			return $template;
		
		$default_path = trailingslashit( SP_RESULTS_MATRIX_DIR ) . 'templates/';

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
		$formats['calendar']['matrix'] = __( 'Matrix', 'sportspress' );

		return $formats;
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-results-matrix'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_RESULTS_MATRIX_URL ) . 'css/sportspress-results-matrix.css',
			'deps'    => 'sportspress-general',
			'version' => SP_RESULTS_MATRIX_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add event matrix shortcode.
	 *
	 * @param array $atts
	 */
	public function shortcode( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();

		echo SP_Shortcodes::shortcode_wrapper( array( $this, 'get_template' ), $atts );

		return ob_get_clean();
	}

	/**
	 * Get event matrix template.
	 *
	 * @param array $atts
	 */
	public function get_template( $atts ) {
		sp_get_template( 'event-matrix.php', $atts, '', trailingslashit( SP_RESULTS_MATRIX_DIR ) . 'templates/' );
	}

	/**
	 * Add shortcodes to TinyMCE
	 */
	public function add_shortcodes( $shortcodes ) {
		$shortcodes['event'][] = 'matrix';
		return $shortcodes;
	}

	/**
	 * Add strings to TinyMCE
	 */
	public function add_tinymce_strings( $strings ) {
		$strings['matrix'] = __( 'Matrix', 'sportspress' );
		return $strings;
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'sportspress-results-matrix-admin', SP_RESULTS_MATRIX_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}
}

endif;

if ( get_option( 'sportspress_load_results_matrix_module', 'yes' ) == 'yes' ) {
	new SportsPress_Results_Matrix();
}
