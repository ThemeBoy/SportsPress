<?php
/*
Plugin Name: SportsPress WordPay
Plugin URI: http://themeboy.com/
Description: Add SportsPress filters to WordPay.
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
		add_action( 'wpay_register_form_after_fields', array( $this, 'form_field' ), 10, 2 );
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
	 * Add field to registration form.
	 */
	public function form_field( $atts = array() ) {
    if ( 'yes' === get_option( 'sportspress_registration_team_input', 'no' ) ) {
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
		}
	}
}

endif;

if ( get_option( 'sportspress_load_wordpay_module', 'yes' ) == 'yes' ) {
	new SportsPress_WordPay();
}