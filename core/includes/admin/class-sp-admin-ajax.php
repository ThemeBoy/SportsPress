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
}

return new SP_Admin_AJAX();
