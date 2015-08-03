<?php
/*
Plugin Name: SportsPress Duplicator
Plugin URI: http://tboy.co/pro
Description: Add a duplicate button to SportsPress post types.
Author: ThemeBoy
Author URI: http://themeboy.com
Version: 1.9
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'SportsPress_Duplicator' ) ) :

/**
 * Main SportsPress Duplicator Class
 *
 * @class SportsPress_Duplicator
 * @version	1.9
 *
 * Code adapted from Duplicate Post by lopo <https://wordpress.org/plugins/duplicate-post/>
 */
class SportsPress_Duplicator {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Define constants
		$this->define_constants();

		// Hooks
		add_filter( 'post_row_actions', array( $this, 'post_row_action' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'post_row_action' ), 10, 2 );
		add_action( 'admin_action_sportspress_duplicate', array( $this, 'duplicate' ) );
	}

	/**
	 * Define constants
	 */
	private function define_constants() {
		if ( !defined( 'SP_DUPLICATOR_VERSION' ) )
			define( 'SP_DUPLICATOR_VERSION', '1.9' );

		if ( !defined( 'SP_DUPLICATOR_URL' ) )
			define( 'SP_DUPLICATOR_URL', plugin_dir_url( __FILE__ ) );

		if ( !defined( 'SP_DUPLICATOR_DIR' ) )
			define( 'SP_DUPLICATOR_DIR', plugin_dir_path( __FILE__ ) );
	}

	function post_row_action( $actions, $post ) {
		if ( $this->allowed( $post->post_type ) && is_sp_post_type( $post->post_type ) ) {
			$actions = array_slice( $actions, 0, 2, true ) +
            array( 'duplicate' => '<a href="' . $this->link( $post ) . '" title="'
			. esc_attr__( 'Duplicate this item', 'sportspress' )
			. '">' .  __( 'Duplicate', 'sportspress' ) . '</a>' ) +
            array_slice( $actions, 2, NULL, true );
		}
		return $actions;
	}

	function link( $post ) {
		if ( ! $this->allowed( $post->post_type ) )
			return;

		if ( ! $post = get_post( $post->ID ) )
			return;

		$action = '?action=sportspress_duplicate&amp;post=' . $post->ID;

		$post_type_object = get_post_type_object( $post->post_type );
		
		if ( ! $post_type_object )
			return;

		return admin_url( 'admin.php'. $action );
	}

	function duplicate() {
		if ( ! ( isset( $_REQUEST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sportspress_duplicate' == $_REQUEST['action'] ) ) ) {
			wp_die( __( 'ERROR: Duplication failed.', 'sportspress' ) );
		}

		// Get the original post
		$id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
		$post = get_post( $id );

		// Copy the post and insert it
		if ( isset( $post ) && null != $post ) {
			$new_id = $this->create_duplicate( $post );

			wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;

		} else {
			wp_die( __( 'ERROR: Duplication failed.', 'sportspress' ) );
		}
	}

	/**
	 * Create a duplicate from a post
	 */
	function create_duplicate( $post ) {

		// We don't want to duplicate revisions
		if ( 'revision' == $post->post_type ) return;

		$new_post_author = $this->get_current_user();

		$new_post = array(
			'menu_order' => $post->menu_order,
			'comment_status' => $post->comment_status,
			'ping_status' => $post->ping_status,
			'post_author' => $new_post_author->ID,
			'post_content' => $post->post_content,
			'post_excerpt' => $post->post_excerpt,
			'post_mime_type' => $post->post_mime_type,
			'post_parent' => $new_post_parent = $post->post_parent,
			'post_password' => $post->post_password,
			'post_status' => $new_post_status = $post->post_status,
			'post_title' => $post->post_title . ' (' . __( 'Copy', 'sportspress' ) . ')',
			'post_type' => $post->post_type,
			'post_date' => $new_post_date = $post->post_date,
			'post_date_gmt' => get_gmt_from_date( $new_post_date ),
		);

		$new_post_id = wp_insert_post( $new_post );

		// Add all post meta to the copy.
		$post_meta = get_post_meta( $post->ID );
		foreach ( $post_meta as $key => $values ) {
			foreach ( $values as $value ) {
				add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
			}
		}

		// Add all terms to the copy.
		$taxonomies = get_object_taxonomies( $post->post_type );
		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_the_terms( $post->ID, $taxonomy );
			if ( ! is_array( $terms ) || ! sizeof( $terms ) ) continue;
			$terms = wp_list_pluck( $terms, 'term_id' );
			wp_set_object_terms( $new_post_id, $terms, $taxonomy );
		}

		// If the copy is published or scheduled, we have to set a proper slug.
		if ( $new_post_status == 'publish' || $new_post_status == 'future' ){
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $new_post_status, $post->post_type, $new_post_parent );

			$new_post = array();
			$new_post['ID'] = $new_post_id;
			$new_post['post_name'] = $post_name;

			// Update the post into the database
			wp_update_post( $new_post );
		}

		do_action( 'sportspress_duplicate_post', $new_post_id, $post );

		delete_post_meta( $new_post_id, '_sp_original' );
		add_post_meta( $new_post_id, '_sp_original', $post->ID );

		return $new_post_id;
	}

	function get_current_user() {
		if ( function_exists( 'wp_get_current_user' ) ) {
			return wp_get_current_user();
		} else if ( function_exists( 'get_currentuserinfo' ) ) {
			global $userdata;
			get_currentuserinfo();
			return $userdata;
		} else {
			$user_login = $_COOKIE[ USER_COOKIE ];
			$sql = $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login=%s", $user_login );
			$current_user = $wpdb->get_results( $sql );			
			return $current_user;
		}
	}

	function allowed( $post_type ) {
		$object = get_post_type_object( $post_type );
		$capability_type = $object->capability_type;
		return current_user_can( "publish_{$capability_type}s" );
	}
}

endif;

if ( get_option( 'sportspress_load_duplicator_module', 'yes' ) == 'yes' ) {
	new SportsPress_Duplicator();
}
