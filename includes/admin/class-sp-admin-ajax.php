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
 * @version     1.5
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

		$post_id = sp_array_value( $_POST, 'post_id' );
		$results = sp_array_value( $_POST, 'results' );
		$main_result = get_option( 'sportspress_primary_result', null );

		if ( ! $post_id || ! is_array( $results ) ) {
			// Return error
			wp_send_json_error();
		}

		// Get current results meta
		$meta = get_post_meta( $post_id, 'sp_results', true );

		$primary_results = array();
		foreach ( $results as $result ) {
			$id = sp_array_value( $result, 'id' );
			$key = sp_array_value( $result, 'key' );

			$primary_results[ $id ] = sp_array_value( $result, 'result', null );

			if ( ! $id || ! $key ) continue;

			$meta[ $id ][ $key ] = sp_array_value( $result, 'result' );
		}

		arsort( $primary_results );

		if ( count( $primary_results ) && ! in_array( null, $primary_results ) ) {
			if ( count( array_unique( $primary_results ) ) === 1 ) {
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '=',
				);
				$outcomes = get_posts( $args );
				foreach ( $meta as $team => $team_results ) {
					if ( $outcomes ) {
						$meta[ $team ][ 'outcome' ] = array();
						foreach ( $outcomes as $outcome ) {
							$meta[ $team ][ 'outcome' ][] = $outcome->post_name;
						}
					}
				}
			} else {
				reset( $primary_results );
				$max = key( $primary_results );
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '>',
				);
				$outcomes = get_posts( $args );
				if ( $outcomes ) {
					$meta[ $max ][ 'outcome' ] = array();
					foreach ( $outcomes as $outcome ) {
						$meta[ $max ][ 'outcome' ][] = $outcome->post_name;
					}
				}

				end( $primary_results );
				$min = key( $primary_results );
				$args = array(
					'post_type' => 'sp_outcome',
					'numberposts' => -1,
					'posts_per_page' => -1,
					'meta_key' => 'sp_condition',
					'meta_value' => '<',
				);
				$outcomes = get_posts( $args );
				if ( $outcomes ) {
					$meta[ $min ][ 'outcome' ] = array();
					foreach ( $outcomes as $outcome ) {
						$meta[ $min ][ 'outcome' ][] = $outcome->post_name;
					}
				}
			}
		}

		// Update results
		update_post_meta( $post_id, 'sp_results', $meta );

		// Return success
		wp_send_json_success();
	}
}

return new SP_Admin_AJAX();
