<?php
/**
 * Admin functions for post types
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Post Types
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
		add_filter( 'request', array( $this, 'custom_columns_orderby' ) );
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

			$strings['insertIntoPost']     = sprintf( __( 'Insert into %s', 'woocommerce' ), $obj->labels->singular_name );
			$strings['uploadedToThisPost'] = sprintf( __( 'Uploaded to this %s', 'woocommerce' ), $obj->labels->singular_name );
		}

		return $strings;
	}

	/**
	 * Custom column orderby
	 *
	 * http://scribu.net/wordpress/custom-sortable-columns.html#comment-4732
	 *
	 * @access public
	 * @param mixed $vars
	 * @return array
	 */
	public function custom_columns_orderby( $vars ) {
		if (isset( $vars['orderby'] )) :
			if ( 'sp_views' == $vars['orderby'] ) :
				$vars = array_merge( $vars, array(
					'meta_key' 	=> 'sp_views',
					'orderby' 	=> 'meta_value_num'
				) );
			endif;
		endif;

		return $vars;
	}
}

endif;