<?php
/**
 * Table Data
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Data
 */
class SP_Meta_Box_Table_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		list( $columns, $usecolumns, $data, $placeholders, $merged ) = sportspress_get_league_table_data( $post->ID, true );

		sportspress_edit_league_table( $columns, $usecolumns, $data, $placeholders );

		sportspress_nonce();
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sportspress_array_value( $_POST, 'sp_columns', array() ) );
		update_post_meta( $post_id, 'sp_teams', sportspress_array_value( $_POST, 'sp_teams', array() ) );
	}
}