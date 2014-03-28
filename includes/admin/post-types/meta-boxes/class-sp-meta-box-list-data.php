<?php
/**
 * List Data
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Data
 */
class SP_Meta_Box_List_Data {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		list( $columns, $usecolumns, $data, $placeholders, $merged ) = sp_get_player_list_data( $post->ID, true );

		sp_edit_player_list_table( $columns, $usecolumns, $data, $placeholders );
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
		update_post_meta( $post_id, 'sp_players', sp_array_value( $_POST, 'sp_players', array() ) );
	}
}