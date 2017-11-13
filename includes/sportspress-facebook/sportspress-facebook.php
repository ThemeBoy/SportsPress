<?php
/*
Plugin Name: SportsPress Facebook
Plugin URI: http://themeboy.com/
Description: Add Facebook Page widget to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.5
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Facebook' ) ) :

/**
 * Main SportsPress Facebook Class
 *
 * @class SportsPress_Facebook
 * @version	2.5
 */
class SportsPress_Facebook {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_team_meta', array( $this, 'save_meta' ), 15, 2 );
		add_action( 'sportspress_widgets', array( $this, 'widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_FACEBOOK_VERSION' ) )
			define( 'SP_FACEBOOK_VERSION', '2.5' );

		if ( !defined( 'SP_FACEBOOK_URL' ) )
			define( 'SP_FACEBOOK_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_FACEBOOK_DIR' ) )
			define( 'SP_FACEBOOK_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_facebookdiv', __( 'Facebook', 'sportspress' ), array( $this, 'meta_box' ), 'sp_team', 'side', 'default' );
	}

	/**
	 * Output the meta box.
	 */
	public static function meta_box( $post ) {
		$url = get_post_meta( $post->ID, 'sp_facebook', true );
		?>
		<p><strong><?php _e( 'Page URL', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_facebook" name="sp_facebook" value="<?php echo esc_url( $url ); ?>" placeholder="https://www.facebook.com/{your-page-name}"></p>
		<?php
	}

	/**
	 * Save Facebook Page URL.
	 */
	public static function save_meta( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_facebook', esc_url( sp_array_value( $_POST, 'sp_facebook', '' ) ) );
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		wp_enqueue_script( 'sportspress-facebook-sdk', SP_FACEBOOK_URL .'js/sportspress-facebook-sdk.js', array(), '2.11' );
	}

	/**
	 * Register widgets
	 */
	public static function widgets() {
		include_once( 'includes/class-sp-widget-facebook.php' );
	}
}

endif;

if ( get_option( 'sportspress_load_facebook_module', 'yes' ) == 'yes' ) {
	new SportsPress_Facebook();
}
