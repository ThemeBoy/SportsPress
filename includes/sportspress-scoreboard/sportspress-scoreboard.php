<?php
/*
Plugin Name: SportsPress Scoreboard
Plugin URI: http://tboy.co/pro
Description: Adds a scoreboard layout to SportsPress event calendars.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.6.11
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Scoreboard' ) ) :

/**
 * Main SportsPress Scoreboard Class
 *
 * @class SportsPress_Scoreboard
 * @version	2.6.11
 */
class SportsPress_Scoreboard {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_shortcode( 'event_scoreboard', array( $this, 'shortcode' ) );
		add_filter( 'sportspress_shortcodes', array( $this, 'add_shortcodes' ) );
		add_filter( 'sportspress_tinymce_strings', array( $this, 'add_tinymce_strings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'sportspress_frontend_css', array( $this, 'frontend_css' ) );
		add_action( 'sportspress_header', array( $this, 'header' ), 30 );
		add_action( 'wp_footer', array( $this, 'footer' ), 30 );
	    add_filter( 'sportspress_enable_header', '__return_true' );
	}

	/**
	 * Define constants.
	 */
	private function define_constants() {
		if ( !defined( 'SP_SCOREBOARD_VERSION' ) )
			define( 'SP_SCOREBOARD_VERSION', '2.6.11' );

		if ( !defined( 'SP_SCOREBOARD_URL' ) )
			define( 'SP_SCOREBOARD_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_SCOREBOARD_DIR' ) )
			define( 'SP_SCOREBOARD_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required ajax files.
	 */
	public function ajax_includes() {
		include_once( 'includes/class-sp-scoreboard-ajax.php' );
	}

	/**
	 * Add settings.
	 *
	 * @return array
	 */
	public function add_settings( $settings ) {
		$options = array(
			0 => __( '&mdash; None &mdash;', 'sportspress' ),
		);
		
		$calendars = get_posts( array( 'post_type' => 'sp_calendar', 'posts_per_page' => 500 ) );
		if ( $calendars ) {
			foreach ( $calendars as $calendar ) {
				$options[ $calendar->ID ] = $calendar->post_title;
			}
		}
		
		$settings = array_merge( $settings,
			array(
				array( 'title' => __( 'Scoreboard', 'sportspress' ), 'type' => 'title', 'id' => 'scoreboard_options' ),
			),

			apply_filters( 'sportspress_scoreboard_options', array(				
				array(
					'title'     => __( 'Details', 'sportspress' ),
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
					'desc' 		=> __( 'League', 'sportspress' ),
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
					'title' 	=> __( 'Date Format', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_date_format',
					'class' 	=> 'small-text',
					'default'	=> 'M j',
					'type' 		=> 'text',
				),

				array(
					'title'     => __( 'Teams', 'sportspress' ),
					'desc' 		=> __( 'Display logos', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_show_logos',
					'default'	=> 'no',
					'type' 		=> 'checkbox',
				),

				array(
					'title' 	=> __( 'Display', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_limit',
					'class' 	=> 'small-text',
					'default'	=> '0',
					'desc' 		=> __( 'events', 'sportspress' ),
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 0,
						'step' 	=> 1
					),
				),

				array(
					'title' 	=> __( 'Width', 'sportspress' ),
					'id' 		=> 'sportspress_scoreboard_width',
					'class' 	=> 'small-text',
					'default'	=> '180',
					'desc' 		=> 'px',
					'type' 		=> 'number',
					'custom_attributes' => array(
						'min' 	=> 50,
						'step' 	=> 1
					),
				),

				array(
					'title' 	=> __( 'Scroll', 'sportspress' ),
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
		
		$default_path = trailingslashit( SP_SCOREBOARD_DIR ) . 'templates/';

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
		$styles['sportspress-scoreboard'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SCOREBOARD_URL ) . 'css/sportspress-scoreboard.css',
			'deps'    => 'sportspress-general',
			'version' => SP_SCOREBOARD_VERSION,
			'media'   => 'all'
		);

		if ( is_rtl() ) {
			$styles['sportspress-scoreboard-rtl'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SCOREBOARD_URL ) . 'css/sportspress-scoreboard-rtl.css',
				'deps'    => 'sportspress-scoreboard',
				'version' => SP_SCOREBOARD_VERSION,
				'media'   => 'all'
			);
		} else {
			$styles['sportspress-scoreboard-ltr'] = array(
				'src'     => str_replace( array( 'http:', 'https:' ), '', SP_SCOREBOARD_URL ) . 'css/sportspress-scoreboard-ltr.css',
				'deps'    => 'sportspress-scoreboard',
				'version' => SP_SCOREBOARD_VERSION,
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
		wp_enqueue_script( 'sportspress-scoreboard', SP_SCOREBOARD_URL .'js/sportspress-scoreboard.js', array( 'jquery' ), time(), true );
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		include_once( 'includes/class-sp-widget-event-scoreboard.php' );
	}

	/**
	 * Add scoreboard shortcode.
	 *
	 * @param array $atts
	 */
	public static function shortcode( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) )
			$atts['id'] = $atts[0];

		ob_start();

		echo SP_Shortcodes::shortcode_wrapper( array( $this, 'get_template' ), $atts );

		return ob_get_clean();
	}

	/**
	 * Get scoreboard template.
	 *
	 * @param array $atts
	 */
	public static function get_template( $atts ) {
		sp_get_template( 'event-scoreboard.php', $atts, '', trailingslashit( SP_SCOREBOARD_DIR ) . 'templates/' );
	}

	/**
	 * Add shortcodes to TinyMCE
	 */
	public static function add_shortcodes( $shortcodes ) {
		$shortcodes['event'][] = 'scoreboard';
		return $shortcodes;
	}

	/**
	 * Add strings to TinyMCE
	 */
	public static function add_tinymce_strings( $strings ) {
		$strings['scoreboard'] = __( 'Scoreboard', 'sportspress' );
		return $strings;
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( 'sportspress-scoreboard-admin', SP_SCOREBOARD_URL . 'css/admin.css', array( 'sportspress-admin-menu-styles' ), time() );
	}

	/**
	 * Frontend CSS
	 */
	public static function frontend_css( $colors ) {
		if ( current_theme_supports( 'sportspress' ) )
			return;

		if ( isset( $colors['heading'] ) ) {
			echo '.sp-template-scoreboard .sp-scoreboard-nav{color:' . $colors['heading'] . ' !important}';
		}
		if ( isset( $colors['link'] ) ) {
			echo '.sp-template-scoreboard .sp-scoreboard-nav{background-color:' . $colors['link'] . ' !important}';
		}
	}

	/**
	 * Header scoreboard
	 */
	public static function header() {
		$limit = get_option( 'sportspress_scoreboard_limit', 0 );
		
		if ( ! $limit )
			return;
		?>
		<div class="sp-header-scoreboard">
			<?php sp_get_template( 'event-scoreboard.php', array( 'number' => $limit ), '', trailingslashit( SP_SCOREBOARD_DIR ) . 'templates/' ); ?>
		</div>
		<?php
	}

	public static function inline_scripts() {
		?>
			<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.sp-header-loaded').prepend( $('.sp-header-scoreboard') );
			} );
			</script>
		<?php
	}

	public static function footer() {
		if ( did_action( 'sportspress_header' ) ) return;
		self::header();
		self::inline_scripts();
	}
}

endif;

if ( get_option( 'sportspress_load_scoreboard_module', 'yes' ) == 'yes' ) {
	new SportsPress_Scoreboard();
}
