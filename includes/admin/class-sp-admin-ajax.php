<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SportsPress Admin.
 *
 * @class 		SP_Admin_AJAX
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.3
 */
class SP_Admin_AJAX {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_sp-save-primary-result', array( $this, 'save_primary_result' ), 1 );
		add_action( 'wp_ajax_sp-save-inline-results', array( $this, 'save_inline_results' ) );
	}

	/**
	 * Auto-save the selected primary result.
	 *
	 * @since  1.3
	 */
	function save_primary_result() {
		check_ajax_referer( 'sp-save-primary-result', 'nonce' );

		$primary_result = sanitize_key( $_POST['primary_result'] );

		update_option( 'sportspress_primary_result', $primary_result );
		wp_send_json_success();
	}

	/**
	 * Save event results inline.
	 *
	 * @since  1.5
	 */
	function save_inline_results() {
		check_ajax_referer( 'sp-save-inline-results', 'nonce' );

		$post_id = sp_array_value( $_POST, 'post_id' );
		$results = sp_array_value( $_POST, 'results' );

		if ( ! $post_id || ! is_array( $results ) ) {
			// Return error
			wp_send_json_error();
		}

		// Get current results meta
		$meta = get_post_meta( $post_id, 'sp_results', true );

		foreach ( $results as $result ) {
			$id = sp_array_value( $result, 'id' );
			$key = sp_array_value( $result, 'key' );
			if ( ! $id || ! $key ) continue;

			$meta[ $id ][ $key ] = sp_array_value( $result, 'result' );
		}

		// Update results
		update_post_meta( $post_id, 'sp_results', $meta );

		// Return success
		wp_send_json_success();
	}
}

return new SP_Admin_AJAX();
