<?php
/*
Plugin Name: SportsPress Export Enhanced
Plugin URI: http://tboy.co/pro
Description: Add more options during exporting.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 2.8
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Enhanced' ) ) :

/**
 * Main SportsPress Enhanced Class
 *
 * @class SportsPress_Enhanced
 * @version	2.8
 *
 */
class SportsPress_Enhanced {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'sportspress_fixtures_data_export_args', array( $this, 'add_export_args' ), 11 );
		
		add_action( 'sportspress_fixtures_filters', array( $this, 'add_date_filters' ), 11 );
		add_action( 'sportspress_events_filters', array( $this, 'add_date_filters' ), 11 );
		add_action( 'sportspress_global_filters', array( $this, 'add_json_filters' ), 11 );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_EXPORT_ENHANCED_VERSION' ) )
			define( 'SP_EXPORT_ENHANCED_VERSION', '2.8' );

		if ( !defined( 'SP_EXPORT_ENHANCED_URL' ) )
			define( 'SP_EXPORT_ENHANCED_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_EXPORT_ENHANCED_DIR' ) )
			define( 'SP_EXPORT_ENHANCED_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Add more filters
	 */
	public function add_date_filters() {
		?>
		<li class="sp-date-range">
			<label class="sp-date-range-absolute"><span class="label-responsive"><?php _e( 'Date range:', 'sportspress' ); ?>&nbsp;</span>
				<input type="text" class="sp-datepicker-from" name="sp_date_from" value="&mdash; Not set &mdash;" size="10">
				:
				<input type="text" class="sp-datepicker-to" name="sp_date_to" value="&mdash; Not set &mdash;" size="10">
			</label>
		</li>
	<?php
	}
	
	public function add_json_filters() {
		?>
		<li>
			<label><span class="label-responsive"><?php _e( 'Export File Format:', 'sportspress' ); ?>&nbsp;</span>
					<input type="radio" id="post-format-csv" name="sp_file_format" class="post-format" value="csv" checked="checked"> <label for="post-format-csv" class="post-format-icon"><?php _e( 'CSV', 'sportspress' ); ?></label>
					<input type="radio" id="post-format-json"name="sp_file_format" class="post-format" value="json"> <label for="post-format-json" class="post-format-icon"><?php _e( 'JSON', 'sportspress' ); ?></label>
			</label>
		</li>
	<?php
	}
	
	/**
	 * Add export args
	 */
	public function add_export_args( $args ) {
		if ( $_POST['sp_date_from'] != '— Not set —' || $_POST['sp_date_to'] != '— Not set —' ) {
			$args['date_query'] = array(
					array(
						'after' => $_POST['sp_date_from'],
						'before' => array(
							'year' => date( 'Y', strtotime( $_POST['sp_date_to'] ) ),
							'month' => date( 'n', strtotime( $_POST['sp_date_to'] ) ),
							'day' => date( 'j', strtotime( $_POST['sp_date_to'] ) ),
						),
						'inclusive' => true
					),
				);
		}
		return $args;
	}
}

endif;

new SportsPress_Enhanced();
