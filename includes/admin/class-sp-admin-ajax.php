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
 * @version     1.7
 */
class SP_Admin_AJAX {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_sp-save-primary-result', array( $this, 'save_primary_result' ), 1 );
		add_action( 'wp_ajax_sp-save-primary-performance', array( $this, 'save_primary_performance' ), 1 );
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
	 * Auto-save the selected primary performance.
	 *
	 * @since  1.7
	 */
	function save_primary_performance() {
		check_ajax_referer( 'sp-save-primary-performance', 'nonce' );

		$primary_performance = sanitize_key( $_POST['primary_performance'] );

		update_option( 'sportspress_primary_performance', $primary_performance );
		wp_send_json_success();
	}

	/**
	 * Save event results inline.
	 *
	 * @since  1.5
	 */
	function save_inline_results() {
		check_ajax_referer( 'sp-save-inline-results', 'nonce' );

		$id = sp_array_value( $_POST, 'post_id' );
		$results = sp_array_value( $_POST, 'results' );

		if ( sp_update_main_results ( $id, $results ) ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}
}

return new SP_Admin_AJAX();
