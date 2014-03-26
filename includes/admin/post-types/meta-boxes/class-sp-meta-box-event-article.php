<?php
/**
 * Event Article
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Article
 */
class SP_Meta_Box_Event_Article {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_editor( $post->post_content, 'content' );
	}
}