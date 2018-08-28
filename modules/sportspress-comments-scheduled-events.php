<?php
/*
Plugin Name: SportsPress Comments on Scheduled Events
Plugin URI: http://themeboy.com/
Description: Enable commenting on Scheduled Events.
Author: Savvas
Author URI: http://themeboy.com/
Version: 2.6.8
*/
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'SportsPress_Comments_Scheduled_Events' ) ) :
/**
 * Main SportsPress Comments Scheduled Events Class
 *
 * @class SportsPress_Comments_Scheduled_Events
 * @version	2.6.8
 */
class SportsPress_Comments_Scheduled_Events {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();
		
		// Actions
		add_action( 'comment_on_draft', array( $this, 'sp_publish_comment' ) );

	}
	/**
	 * Define constants.
	*/
	private function define_constants() {
		if ( !defined( 'SP_COMMENTS_SCHEDULED_EVENTS_VERSION' ) )
			define( 'SP_COMMENTS_SCHEDULED_EVENTS_VERSION', '2.6.8' );
		if ( !defined( 'SP_COMMENTS_SCHEDULED_EVENTS_URL' ) )
			define( 'SP_COMMENTS_SCHEDULED_EVENTS_URL', plugin_dir_url( __FILE__ ) );
		if ( !defined( 'SP_COMMENTS_SCHEDULED_EVENTS_DIR' ) )
			define( 'SP_COMMENTS_SCHEDULED_EVENTS_DIR', plugin_dir_path( __FILE__ ) );
	}
	
	/**
	 * Save Additional Statistics
	 */
	public function sp_publish_comment( $comment_post_ID ) {
		
		do_action( 'pre_comment_on_post', $comment_post_ID );
 
	$comment_author = ( isset( $_POST['author'] ) ) ? trim( strip_tags( $_POST['author'] ) ) : null;
	$comment_author_email = ( isset( $_POST['email'] ) ) ? sanitize_email ( trim( $_POST['email'] ) ) : null;
	$comment_author_url = ( isset($_POST['url'] ) ) ? esc_url( trim( $_POST['url'] ) ) : null;
	$comment_content = ( isset( $_POST['comment'] ) ) ? esc_textarea( trim( $_POST['comment'] ) ) : null;
	
	// If the user is logged in
	$user = wp_get_current_user();
	if ( $user->exists() ) {
		if ( empty( $user->display_name ) ) {
			$user->display_name=$user->user_login;
		}
		$comment_author       = $user->display_name;
		$comment_author_email = $user->user_email;
		$comment_author_url   = $user->user_url;
		$user_ID              = $user->ID;
		if ( current_user_can( 'unfiltered_html' ) ) {
			if ( ! isset( $comment_data['_wp_unfiltered_html_comment'] )
				|| ! wp_verify_nonce( $comment_data['_wp_unfiltered_html_comment'], 'unfiltered-html-comment_' . $comment_post_ID )
			) {
				kses_remove_filters(); // start with a clean slate
				kses_init_filters(); // set up the filters
			}
		}
	} else {
		if ( get_option( 'comment_registration' ) ) {
			return new WP_Error( 'not_logged_in', __( 'Sorry, you must be logged in to comment.' ), 403 );
		}
	}
	
	$comment_type = '';

	if ( get_option( 'require_name_email' ) && ! $user->exists() ) {
		if ( '' == $comment_author_email || '' == $comment_author ) {
			//return new WP_Error( 'require_name_email', __( '<strong>ERROR</strong>: please fill the required fields (name, email).' ), 200 );
			wp_die( __( '<strong>ERROR</strong>: please fill the required fields (name, email).' ), __( 'ERROR: please fill the required fields (name, email).' ), array ( 'back_link' => true ) );
		} elseif ( ! is_email( $comment_author_email ) ) {
			//return new WP_Error( 'require_valid_email', __( '<strong>ERROR</strong>: please enter a valid email address.' ), 200 );
			wp_die( __( '<strong>ERROR</strong>: please enter a valid email address.' ), __( 'ERROR: please enter a valid email address.' ), array ( 'back_link' => true ) );
		}
	}
	
	if ( '' == $comment_content ) {
		//return new WP_Error( 'require_valid_comment', __( '<strong>ERROR</strong>: please type a comment.' ), 200 );
		wp_die( __( '<strong>ERROR</strong>: please type a comment.' ), __( 'ERROR: please type a comment.' ), array ( 'back_link' => true ) );
	}
	
	$comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
	
	$commentdata = compact(
		'comment_post_ID',
		'comment_author',
		'comment_author_email',
		'comment_author_url',
		'comment_content',
		'comment_type',
		'comment_parent',
		'user_ID'
	);
	
	$check_max_lengths = wp_check_comment_data_max_lengths( $commentdata );
	if ( is_wp_error( $check_max_lengths ) ) {
		return $check_max_lengths;
	}
	
	$comment_id = wp_new_comment( wp_slash( $commentdata ), true );
	if ( is_wp_error( $comment_id ) ) {
		return $comment_id;
	}
	
	if ( ! $comment_id ) {
		//return new WP_Error( 'comment_save_error', __( '<strong>ERROR</strong>: The comment could not be saved. Please try again later.' ), 500 );
		wp_die( __( '<strong>ERROR</strong>: The comment could not be saved. Please try again later.' ), __( 'ERROR: The comment could not be saved. Please try again later.' ), array ( 'back_link' => true ) );
	}
	
	$comment = get_comment( $comment_id );
	
	do_action( 'set_comment_cookies', $comment, $user );
	
	if ( $user->exists() ) {
		wp_set_comment_status( $comment_id, 'approve' );
	}
	
	$location = empty($_POST['redirect_to']) ? get_comment_link($comment_id) : $_POST['redirect_to'] . '#comment-' . $comment_id;
	
	$location = apply_filters( 'comment_post_redirect', $location, $comment );
	
	wp_safe_redirect( $location );
	exit;
	}
	

}
endif;

new SportsPress_Comments_Scheduled_Events();
