<?php
/**
 * Config type meta box functions
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Config
 */
class SP_Meta_Box_Config {

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
	}

	public static function delete_duplicate( &$post ) {
		global $wpdb;

		$key = isset( $post['sp_key'] ) ? $post['sp_key'] : null;
		if ( ! $key ) $key = $post['post_title'];
		$id = sp_array_value( $post, 'post_ID', 'var' );
		$title = sp_get_eos_safe_slug( $key, $id );

		$check_sql = "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s AND ID != %d LIMIT 1";
		$post_name_check = $wpdb->get_var( $wpdb->prepare( $check_sql, $title, $post['post_type'], $id ) );

		if ( $post_name_check ):
			wp_delete_post( $post_name_check, true );
			$post['post_status'] = 'draft';
		endif;

		return $post_name_check;
	}
}
