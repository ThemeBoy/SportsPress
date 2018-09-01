<?php
/*
Plugin Name: SportsPress Event Scoresheet
Plugin URI: http://themeboy.com/
Description: Add Event Scoresheet uploadin support for Events to SportsPress.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.7
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Scoresheet' ) ) :

/**
 * Main SportsPress Event Scoresheet Class
 *
 * @class SportsPress_Event_Scoresheet
 * @version	2.7
 */
class SportsPress_Event_Scoresheet {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_event_meta', array( $this, 'save_meta' ), 15, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		
		add_filter( 'sportspress_calendar_columns', array( $this, 'calendar_columns' ) );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EVENT_SCORESHET_VERSION' ) )
			define( 'SP_EVENT_SCORESHET_VERSION', '2.7' );

		if ( !defined( 'SP_EVENT_SCORESHET_URL' ) )
			define( 'SP_EVENT_SCORESHET_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_SCORESHET_DIR' ) )
			define( 'SP_EVENT_SCORESHET_DIR', plugin_dir_path( __FILE__ ) );
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
		add_meta_box( 'sp_event_scoresheet_div', __( 'Event Scoresheet', 'sportspress' ), array( $this, 'meta_box' ), 'sp_event', 'side', 'default' );
	}

	/**
	 * Output the meta box.
	 */
	public static function meta_box( $post ) {
		$scoresheet = get_post_meta( $post->ID, 'sp_scoresheet', true );
		if ( $scoresheet ):
		?>
		<fieldset class="sp-scoresheet-show">
			<?php echo wp_get_attachment_image( $scoresheet, 'thumbnail', true ); ?>
			<p><a href="#" class="sp-remove-scoresheet"><?php _e( 'Remove Scoresheet', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php endif; ?>
		<fieldset class="sp-scoresheet-field hidden">
			<p><strong><?php _e( 'Scoresheet URL', 'sportspress' ); ?></strong></p>
			<p><input class="widefat" type="text" name="sp_scoresheet_url" id="sp_upload_scoresheet_url" value=""></p>
			<p><input type="text" name="sp_scoresheet" id="sp_upload_scoresheet" value="<?php echo $scoresheet; ?>" hidden></p>
			<p><a href="#" class="sp-remove-scoresheet"><?php _e( 'Cancel', 'sportspress' ); ?></a></p>
		</fieldset>
		<fieldset class="sp-scoresheet-adder<?php if ( $scoresheet ): ?> hidden<?php endif; ?>">
			<p><a href="#" class="sp-add-scoresheet"><?php _e( 'Add Scoresheet', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php
	}

	/**
	 * Save Facebook Page URL.
	 */
	public static function save_meta( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_scoresheet', $_POST['sp_scoresheet'] );
	}

	/**
	 * Register/queue frontend scripts.
	 *
	 * @access public
	 * @return void
	 */
	public function load_scripts() {
		if( function_exists( 'wp_enqueue_media' ) )
			wp_enqueue_media();
		
		wp_register_script( 'sportspress-event-scoresheet', SP_EVENT_SCORESHET_URL .'js/sportspress-event-scoresheet.js', array( 'jquery' ), '2.7.0' );
		wp_enqueue_script( 'sportspress-event-scoresheet' );
	}
	
	/**
	 * Add calendar columns.
	 *
	 * @return array
	 */
	public function calendar_columns( $columns = array() ) {
		$columns['scoresheet'] = __( 'Scoresheet', 'sportspress' );
		return $columns;
	}

}

endif;

if ( get_option( 'sportspress_load_event_scoresheet_module', 'yes' ) == 'yes' ) {
	new SportsPress_Event_Scoresheet();
}
