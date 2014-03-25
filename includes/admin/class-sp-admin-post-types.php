<?php
/**
 * Post Types Admin
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Post_Types' ) ) :

/**
 * SP_Admin_Post_Types Class
 */
class SP_Admin_Post_Types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'include_post_type_handlers' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	/**
	 * Conditonally load classes and functions only needed when viewing a post type.
	 */
	public function include_post_type_handlers() {
		//include( 'post-types/class-sp-admin-meta-boxes.php' );
		include( 'post-types/class-sp-admin-cpt-event.php' );
		include( 'post-types/class-sp-admin-cpt-team.php' );
		include( 'post-types/class-sp-admin-cpt-player.php' );
	}

	/**
	 * Change messages when a post type is updated.
	 *
	 * @param  array $messages
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['product'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( 'Product updated. <a href="%s">View Product</a>', 'sportspress' ), esc_url( get_permalink($post_ID) ) ),
			2 => __( 'Custom field updated.', 'sportspress' ),
			3 => __( 'Custom field deleted.', 'sportspress' ),
			4 => __( 'Product updated.', 'sportspress' ),
			5 => isset($_GET['revision']) ? sprintf( __( 'Product restored to revision from %s', 'sportspress' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Product published. <a href="%s">View Product</a>', 'sportspress' ), esc_url( get_permalink($post_ID) ) ),
			7 => __( 'Product saved.', 'sportspress' ),
			8 => sprintf( __( 'Product submitted. <a target="_blank" href="%s">Preview Product</a>', 'sportspress' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Product</a>', 'sportspress' ),
			  date_i18n( __( 'M j, Y @ G:i', 'sportspress' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __( 'Product draft updated. <a target="_blank" href="%s">Preview Product</a>', 'sportspress' ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		$messages['shop_order'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Order updated.', 'sportspress' ),
			2 => __( 'Custom field updated.', 'sportspress' ),
			3 => __( 'Custom field deleted.', 'sportspress' ),
			4 => __( 'Order updated.', 'sportspress' ),
			5 => isset($_GET['revision']) ? sprintf( __( 'Order restored to revision from %s', 'sportspress' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Order updated.', 'sportspress' ),
			7 => __( 'Order saved.', 'sportspress' ),
			8 => __( 'Order submitted.', 'sportspress' ),
			9 => sprintf( __( 'Order scheduled for: <strong>%1$s</strong>.', 'sportspress' ),
			  date_i18n( __( 'M j, Y @ G:i', 'sportspress' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Order draft updated.', 'sportspress' )
		);

		$messages['shop_coupon'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => __( 'Coupon updated.', 'sportspress' ),
			2 => __( 'Custom field updated.', 'sportspress' ),
			3 => __( 'Custom field deleted.', 'sportspress' ),
			4 => __( 'Coupon updated.', 'sportspress' ),
			5 => isset($_GET['revision']) ? sprintf( __( 'Coupon restored to revision from %s', 'sportspress' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => __( 'Coupon updated.', 'sportspress' ),
			7 => __( 'Coupon saved.', 'sportspress' ),
			8 => __( 'Coupon submitted.', 'sportspress' ),
			9 => sprintf( __( 'Coupon scheduled for: <strong>%1$s</strong>.', 'sportspress' ),
			  date_i18n( __( 'M j, Y @ G:i', 'sportspress' ), strtotime( $post->post_date ) ) ),
			10 => __( 'Coupon draft updated.', 'sportspress' )
		);

		return $messages;
	}
}

endif;

return new SP_Admin_Post_Types();