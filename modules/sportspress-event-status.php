<?php
/*
Plugin Name: SportsPress Event Status
Plugin URI: http://themeboy.com/
Description: Add a status option to SportsPress events.
Author: ThemeBoy
Author URI: http://themeboy.com/
Version: 2.1
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Event_Status' ) ) :

/**
 * Main SportsPress Event Status Class
 *
 * @class SportsPress_Event_Status
 * @version	2.1
 */
class SportsPress_Event_Status {

	/**
	 * @var array
	 */
	public $statuses = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		add_action( 'init', array( $this, 'get_statuses' ) );
		add_action( 'post_submitbox_misc_actions', array( $this, 'section' ) );
		add_action( 'sportspress_process_sp_event_meta', array( $this, 'save' ), 10, 1 );
		add_filter( 'sportspress_event_time', array( $this, 'filter' ), 10, 2 );
		add_filter( 'sportspress_event_time_admin', array( $this, 'filter' ), 10, 2 );
		add_filter( 'sportspress_main_results_or_time', array( $this, 'filter_array' ), 10, 2 );
		add_filter( 'sportspress_event_blocks_team_result_or_time', array( $this, 'filter_array' ), 10, 2 );
	}

	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_EVENT_STATUS_VERSION' ) )
			define( 'SP_EVENT_STATUS_VERSION', '2.1' );

		if ( !defined( 'SP_EVENT_STATUS_URL' ) )
			define( 'SP_EVENT_STATUS_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EVENT_STATUS_DIR' ) )
			define( 'SP_EVENT_STATUS_DIR', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Define statuses.
	*/
	public function get_statuses() {
		$this->statuses = apply_filters( 'sportspress_event_statuses', array(
			'ok' => __( 'On time', 'sportspress' ),
			'tbd' => __( 'TBD', 'sportspress' ),
			'postponed' => __( 'Postponed', 'sportspress' ),
			'cancelled' => __( 'Canceled', 'sportspress' ),
		) );
	}

	/**
	 * Add status section to submit box.
	 */
	public function section() {
		if ( 'sp_event' !== get_post_type() ) return;
		$status = get_post_meta( get_the_ID(), 'sp_status', true );
		if ( ! $status ) $status = 'ok';
		?>
		<div class="misc-pub-section sp-pub-event-status">
			<span class="sp-event-status"><?php _e( 'Time:', 'sportspress' ); ?> <strong class="sp-event-status-display" data-sp-event-status="<?php echo $status; ?>"><?php echo $this->statuses[ $status ]; ?></strong></span>
			<a href="#" class="sp-edit-event-status hide-if-no-js"><span aria-hidden="true"><?php _e( 'Edit', 'sportspress' ); ?></span> <span class="screen-reader-text"><?php _e( 'Edit status' ); ?></span></a>
			<div class="sp-event-status-select hide-if-js">
				<?php foreach ( $this->statuses as $value => $label ) { ?>
					<label><input type="radio" name="sp_status" value="<?php echo $value; ?>" data-sp-event-status="<?php echo $label; ?>" <?php checked( $status, $value ); ?>> <?php echo $label; ?></label><br>
				<?php } ?>
				<p>
					<a href="#" class="sp-save-event-status hide-if-no-js button">OK</a>
					<a href="#" class="sp-cancel-event-status hide-if-no-js button-cancel">Cancel</a>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Save status option.
	 */
	public function save( $post_id ) {
		update_post_meta( $post_id, 'sp_status', sp_array_value( $_POST, 'sp_status', 'ok' ) );
	}

	/**
	 * Event time filter.
	 */
	public function filter( $time, $post_id = 0 ) {
		if ( ! $post_id ) $post_id = get_the_ID();
		$status = get_post_meta( $post_id, 'sp_status', true );
		if ( ! $status || 'ok' === $status || ! array_key_exists( $status, $this->statuses ) ) return $time;
		return $this->statuses[ $status ];
	}

	/**
	 * Event time array filter.
	 */
	public function filter_array( $array, $post_id = 0 ) {
		if ( ! $post_id ) $post_id = get_the_ID();
		$status = get_post_meta( $post_id, 'sp_status', true );
		if ( ! $status || 'ok' === $status || ! array_key_exists( $status, $this->statuses ) ) return $array;
		return array( $this->statuses[ $status ] );
	}
}

endif;

new SportsPress_Event_Status();
