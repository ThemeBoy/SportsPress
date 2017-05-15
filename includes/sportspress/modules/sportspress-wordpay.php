<?php
/*
Plugin Name: SportsPress WordPay
Plugin URI: http://themeboy.com/
Description: Add team and player registration shortcodes to WordPay.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.3
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_WordPay' ) ) :

/**
 * Main SportsPress WordPay Class
 *
 * @class SportsPress_WordPay
 * @version	2.3
 */
class SportsPress_WordPay {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Actions
		add_action( 'init', array( $this, 'add_shortcodes' ) );
		add_action( 'wpay_register_form_after_fields', array( $this, 'form_field' ) );

		// Filters
		add_filter( 'wordpay_shortcodes', array( $this, 'editor_shortcodes' ) );
		add_filter( 'wordpay_tinymce_strings', array( $this, 'editor_strings' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_WORDPAY_VERSION' ) )
			define( 'SP_WORDPAY_VERSION', '2.3' );

		if ( !defined( 'SP_WORDPAY_URL' ) )
			define( 'SP_WORDPAY_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_WORDPAY_DIR' ) )
			define( 'SP_WORDPAY_DIR', plugin_dir_path( __FILE__ ) );
	}
	/**
	 * Add team and player registration shortcodes.
	 */
	public static function add_shortcodes() {
		add_shortcode( 'wpay-register-team', array( $this, 'register_team' ) );
		add_shortcode( 'wpay-register-player', array( $this, 'register_player' ) );
	}

	/**
	 * Team registration shortcode.
	 */
	public static function register_team( $atts = array() ) {
		return self::register_form( $atts, 'team' );
	}

	/**
	 * Player registration shortcode.
	 */
	public static function register_player( $atts = array() ) {
		return self::register_form( $atts, 'player' );
	}

	/**
	 * Registration form template.
	 */
	public static function register_form( $atts = array(), $context = '' ) {

    $atts = shortcode_atts( array(
        'subscription_plans' => array(),
        'plans_position'     => 'bottom',
        'selected'           => '',
    ), $atts );

    if ( is_array( $atts['subscription_plans'] ) ) {
    	$atts['subscription_plans'] = implode( ',', $atts['subscription_plans'] );
    }

    $atts['context'] = $context;

    $shortcode = '[wpay-register';

    foreach ( $atts as $key => $value ) {
    	$shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
    }

    $shortcode .= ']';

    return do_shortcode( $shortcode );
	}

	/**
	 * Add field to registration form.
	 */
	public static function form_field( $atts = array() ) {
		if ( 'team' == $atts['context'] ) {
			?>
			<li class="wpay-field">
			<label for="wpay_team_name"><?php _e( 'Team Name', 'sportspress' ); ?></label>
			<input id="wpay_team_name" name="team_name" type="text" value="">
			</li>
			<?php
			wp_nonce_field( 'submit_team_name', 'sp_register_form_team' );
		} elseif ( 'player' == $atts['context'] ) {
			?>
			<li class="wpay-field">
				<label for="sp_team"><?php _e( 'Team', 'sportspress' ); ?></label>
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team',
					'values' => 'ID',
					'show_option_none' => sprintf( __( 'Select %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
					'class' => 'widefat',
				);
				sp_dropdown_pages( $args );
				?>
			</li>
			<?php
			wp_nonce_field( 'submit_team', 'sp_register_form_player' );
		}
	}

	/**
	 * Add shortcodes to editor.
	 */
	public static function editor_shortcodes( $shortcodes = array() ) {
		$shortcodes[] = 'register_team';
		$shortcodes[] = 'register_player';
		return $shortcodes;
	}

	/**
	 * Add strings to editor.
	 */
	public static function editor_strings( $strings = array() ) {
		$strings['register_team'] = __( 'Register Team', 'sportspress' );
		$strings['register_player'] = __( 'Register Player', 'sportspress' );
		return $strings;
	}
}

endif;

if ( get_option( 'sportspress_load_wordpay_module', 'yes' ) == 'yes' ) {
	new SportsPress_WordPay();
}