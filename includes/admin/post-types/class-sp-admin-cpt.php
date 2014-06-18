<?php
/**
 * Admin functions for post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post_Types
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_CPT' ) ) :

/**
 * SP_Admin_CPT Class
 */
class SP_Admin_CPT {

	protected $type = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Insert into X media browser
		add_filter( 'media_view_strings', array( $this, 'change_insert_into_post' ) );
	}

	/**
	 * Change label for insert buttons.
	 * @access   public
	 * @param array $strings
	 * @return array
	 */
	function change_insert_into_post( $strings ) {
		global $post_type;

		if ( $post_type == $this->type ) {
			$obj = get_post_type_object( $this->type );

			$strings['insertIntoPost']     = sprintf( __( 'Insert into %s', 'sportspress' ), $obj->labels->singular_name );
			$strings['uploadedToThisPost'] = sprintf( __( 'Uploaded to this %s', 'sportspress' ), $obj->labels->singular_name );
		}

		return $strings;
	}

	/**
	 * Check if we're editing or adding an event
	 * @return boolean
	 */
	private function is_editing() {
		if ( ! empty( $_GET['post_type'] ) && $this->type == $_GET['post_type'] ) {
			return true;
		}
		if ( ! empty( $_GET['post'] ) && $this->type == get_post_type( $_GET['post'] ) ) {
			return true;
		}
		if ( ! empty( $_REQUEST['post_id'] ) && $this->type == get_post_type( $_REQUEST['post_id'] ) ) {
			return true;
		}
		return false;
	}
}

endif;