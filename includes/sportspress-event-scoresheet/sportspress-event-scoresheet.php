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

		// Actions
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
		add_action( 'sportspress_process_sp_event_meta', array( $this, 'save_meta' ), 15, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_action( 'sportspress_calendar_data_meta_box_table_head_row', array( $this, 'calendar_meta_head_row' ) );
		add_action( 'sportspress_calendar_data_meta_box_table_row', array( $this, 'calendar_meta_row' ), 10, 2 );
		add_action( 'sportspress_event_list_head_row', array( $this, 'event_list_head_row' ) );
		add_action( 'sportspress_event_list_row', array( $this, 'event_list_row' ), 10, 2 );
		
		// Filters
		add_filter( 'sportspress_calendar_columns', array( $this, 'calendar_columns' ) );
		add_filter( 'sportspress_text', array( $this, 'add_text_options' ) );
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
		add_meta_box( 'sp_scoresheet_div', __( 'Event Scoresheet', 'sportspress' ), array( $this, 'meta_box' ), 'sp_event', 'side', 'default' );
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
	
	/**
	 * Calendar meta box table head row.
	 */
	public function calendar_meta_head_row( $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'scoresheet', $usecolumns ) ) {
			?>
				<th class="column-officials">
					<label for="sp_columns_scoresheet">
						<?php _e( 'Scoresheet', 'sportspress' ); ?>
					</label>
				</th>
				<?php
		}
	}

	/**
	 * Calendar meta box table row.
	 */
	public function calendar_meta_row( $event, $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'scoresheet', $usecolumns ) ) {
			$scoresheet = get_post_meta( $event->ID, 'sp_scoresheet', true );
			?>
			<td>
				<a href="<?php echo get_edit_post_link( $event->ID ); ?>#sp_scoresheet_div">
				<?php if ( $scoresheet ) : ?>
					<div class="dashicons dashicons-clipboard"></div>
				<?php else: ?>
					<?php _e( 'Add one', 'sportspress' ); ?>
				<?php endif; ?>
				</a>
			</td>
	<?php }
	}
	
	/**
	 * Event list head row.
	 */
	public function event_list_head_row( $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'scoresheet', $usecolumns ) ) {
			?>
			<th class="column-scoresheet">
				<label for="sp_columns_scoresheet">
					<?php _e( 'Scoresheet', 'sportspress' ); ?>
				</label>
			</th>
	<?php }
	}

	/**
	 * Event list row.
	 */
	public function event_list_row( $event, $usecolumns = array() ) {
		if ( is_array( $usecolumns ) && in_array( 'scoresheet', $usecolumns ) ) {
			$scoresheet = get_post_meta( $event->ID, 'sp_scoresheet', true );
				?>
				<td class="data-scoresheet">
				<?php if ( $scoresheet ) : ?>
					<a href="<?php echo wp_get_attachment_url( $scoresheet ); ?>" target="_blank">
						<div class="dashicons dashicons-clipboard"></div>
					</a>
				<?php else: ?>
					<?php _e( '-', 'sportspress' ); ?>
				<?php endif; ?>
					
				</td>
				<?php
		}
	}
	
	/**
	 * Add text options 
	 */
	public function add_text_options( $options = array() ) {
		return array_merge( $options, array(
			__( 'Scoresheet', 'sportspress' ),
		) );
	}

}

endif;

if ( get_option( 'sportspress_load_event_scoresheet_module', 'yes' ) == 'yes' ) {
	new SportsPress_Event_Scoresheet();
}
