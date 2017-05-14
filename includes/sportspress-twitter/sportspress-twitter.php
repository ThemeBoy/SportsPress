<?php
/*
Plugin Name: SportsPress Twitter
Plugin URI: http://themeboy.com/
Description: Add Twitter feed to teams, players, and staff.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.1.2
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Twitter' ) ) :

/**
 * Main SportsPress Twitter Class
 *
 * @class SportsPress_Twitter
 * @version	2.1.2
 */
class SportsPress_Twitter {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

	    add_filter( 'sportspress_enqueue_styles', array( $this, 'add_styles' ) );
		add_filter( 'sportspress_general_settings', array( $this, 'add_options' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
		add_filter( 'sportspress_team_templates', array( $this, 'team_templates' ) );
		add_filter( 'sportspress_player_templates', array( $this, 'player_templates' ) );
		add_filter( 'sportspress_staff_templates', array( $this, 'staff_templates' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_team_meta', array( $this, 'save' ), 15, 2 );
		add_action( 'sportspress_process_sp_player_meta', array( $this, 'save' ), 15, 2 );
		add_action( 'sportspress_process_sp_staff_meta', array( $this, 'save' ), 15, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_TWITTER_VERSION' ) )
			define( 'SP_TWITTER_VERSION', '2.1.2' );

		if ( !defined( 'SP_TWITTER_URL' ) )
			define( 'SP_TWITTER_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_TWITTER_DIR' ) )
			define( 'SP_TWITTER_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Include required files.
	*/
	private function includes() {
	}

	/**
	 * Add styles to SP frontend
	 */
	public function add_styles( $styles = array() ) {
		$styles['sportspress-twitter'] = array(
			'src'     => str_replace( array( 'http:', 'https:' ), '', SP_TWITTER_URL ) . 'css/sportspress-twitter.css',
			'deps'    => 'sportspress-general',
			'version' => SP_TWITTER_VERSION,
			'media'   => 'all'
		);
		return $styles;
	}

	/**
	 * Add options to settings page.
	 *
	 * @return array
	 */
	public function add_options( $settings ) {
		return array_merge( $settings, array(
			array( 'title' => __( 'Twitter', 'sportspress' ), 'type' => 'title', 'id' => 'twitter_options' ),

			array(
				'title'     => __( 'Theme', 'sportspress' ),
				'id'        => 'sportspress_twitter_theme',
				'default'   => 'light',
				'type'      => 'select',
				'options'   => array(
					'light' => __( 'Light', 'sportspress' ),
					'dark' => __( 'Dark', 'sportspress' ),
				),
			),
			
			array(
				'title' 	=> __( 'Limit', 'sportspress' ),
				'id' 		=> 'sportspress_twitter_limit',
				'class' 	=> 'small-text',
				'default'	=> '3',
				'desc' 		=> __( 'tweets', 'sportspress' ),
				'type' 		=> 'number',
				'custom_attributes' => array(
					'min' 	=> 1,
					'step' 	=> 1
				),
			),

			array( 'type' => 'sectionend', 'id' => 'twitter_options' ),
		) );
	}

	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Tweets', 'sportspress' ),
		) );
	}

	/**
	 * Add templates to layout.
	 *
	 * @return array
	 */
	public function templates( $templates = array(), $type = 'team' ) {
		$templates['tweets'] = array(
			'title' => __( 'Tweets', 'sportspress' ),
			'option' => 'sportspress_' . $type . '_show_tweets',
			'action' => array( $this, 'output_tweets' ),
			'default' => 'yes',
		);
		
		return $templates;
	}

	/**
	 * Add templates to team layout.
	 *
	 * @return array
	 */
	public function team_templates( $templates = array() ) {
		return self::templates( $templates, 'team' );
	}

	/**
	 * Add templates to player layout.
	 *
	 * @return array
	 */
	public function player_templates( $templates = array() ) {
		return self::templates( $templates, 'player' );
	}

	/**
	 * Add templates to staff layout.
	 *
	 * @return array
	 */
	public function staff_templates( $templates = array() ) {
		return self::templates( $templates, 'staff' );
	}

	/**
	 * Output tweets.
	 *
	 * @access public
	 * @return void
	 */
	public function output_tweets() {
		sp_get_template( 'tweets.php', array(), '', SP_TWITTER_DIR . 'templates/' );
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes() {
		add_meta_box( 'sp_twitterdiv', __( 'Twitter', 'sportspress' ), array( $this, 'meta_box' ), 'sp_team', 'side', 'default' );
		add_meta_box( 'sp_twitterdiv', __( 'Twitter', 'sportspress' ), array( $this, 'meta_box' ), 'sp_player', 'side', 'default' );
		add_meta_box( 'sp_twitterdiv', __( 'Twitter', 'sportspress' ), array( $this, 'meta_box' ), 'sp_staff', 'side', 'default' );
	}

	/**
	 * Output the meta box.
	 */
	public static function meta_box( $post ) {
		$username = get_post_meta( $post->ID, 'sp_twitter', true );
		?>
		<p><strong><?php _e( 'Username', 'sportspress' ); ?></strong></p>
		<p><input type="text" id="sp_twitter" name="sp_twitter" value="<?php echo $username ? '@' . esc_attr( $username ) : ''; ?>" placeholder="@<?php _e( 'Username', 'sportspress' ); ?>"></p>
		<?php
	}

	/**
	 * Save twitter username.
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_twitter', str_replace( '@', '', sp_array_value( $_POST, 'sp_twitter', null ) ) );
	}
}

endif;

if ( get_option( 'sportspress_load_twitter_module', 'yes' ) == 'yes' ) {
	new SportsPress_Twitter();
}
