<?php
/**
 * SportsPress Sponsors Functions
 *
 * General sponsors functions available on both the front-end and admin.
 *
 * @author 		ThemeBoy
 * @category 	Functions
 * @package 	SportsPress Sponsors
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !function_exists( 'sp_array_value' ) ) {
	function sp_array_value( $arr = array(), $key = 0, $default = null ) {
		return ( isset( $arr[ $key ] ) ? $arr[ $key ] : $default );
	}
}

if ( !function_exists( 'sp_get_url' ) ) {
	function sp_get_url( $post_id ) {
		$url = get_post_meta( $post_id, 'sp_url', true );
		if ( ! $url ) return;
		return ' <a class="sp-link" href="' . $url . '" target="_blank" title="' . __( 'Visit Site', 'sportspress' ) . '">' . $url . '</a>';
	}
}

if ( !function_exists( 'sp_get_post_impressions' ) ) {
	function sp_get_post_impressions( $post_id ) {
	    $count_key = 'sp_impressions';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	    	$count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    endif;
	    return sprintf( _n( '%s impression', '%s impressions', $count, 'sportspress' ), $count );
	}
}

if ( !function_exists( 'sp_set_post_impressions' ) ) {
	function sp_set_post_impressions( $post_id ) {
		$exclude_authenticated = get_option( 'sportspress_exclude_authenticated_sponsor_impressions', 'no' );
		if ( $exclude_authenticated && is_user_logged_in() )
			return;
	    $count_key = 'sp_impressions';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	        $count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    else:
	        $count++;
	        update_post_meta( $post_id, $count_key, $count );
	    endif;
	}
}

if ( !function_exists( 'sp_get_post_clicks' ) ) {
	function sp_get_post_clicks( $post_id ) {
	    $count_key = 'sp_clicks';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	    	$count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    endif;
	    return sprintf( _n( '%s click', '%s clicks', $count, 'sportspress' ), $count );
	}
}

if ( !function_exists( 'sp_set_post_clicks' ) ) {
	function sp_set_post_clicks( $post_id ) {
		$exclude_authenticated = get_option( 'sportspress_exclude_authenticated_sponsor_clicks', 'no' );
		if ( $exclude_authenticated == 'yes' && is_user_logged_in() )
			return;
	    $count_key = 'sp_clicks';
	    $count = get_post_meta( $post_id, $count_key, true );
	    if ( $count == '' ):
	        $count = 0;
	        delete_post_meta( $post_id, $count_key );
	        add_post_meta( $post_id, $count_key, '0' );
	    else:
	        $count++;
	        update_post_meta( $post_id, $count_key, $count );
	    endif;
	}
}