<?php
/**
 * Calendar Events
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Data
 */
class SP_Meta_Box_Calendar_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		list( $data, $usecolumns ) = sp_get_calendar_data( $post->ID, true );
		sp_edit_calendar_table( $data, $usecolumns );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}
}