<?php
/**
 * Team Editor
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Team_Editor
 */
class SP_Meta_Box_Team_Editor {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_editor( $post->post_content, 'content' );
	}
}