<?php
/**
 * SportsPress Template
 *
 * Functions for the templating system.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter( 'body_class', 'sp_body_class' );

/** 
 * WP Header
 *
 * @see  sp_generator_tag()
 */
add_action( 'get_the_generator_html', 'sp_generator_tag', 10, 2 );
add_action( 'get_the_generator_xhtml', 'sp_generator_tag', 10, 2 );

/**
 * Venue Archive Content
 */
add_action( 'loop_start', 'sportspress_output_venue_map' );

/**
 * Adjacent Post Links
 */
add_filter( 'previous_post_link', 'sportspress_hide_adjacent_post_links', 10, 4 );
add_filter( 'next_post_link', 'sportspress_hide_adjacent_post_links', 10, 4 );

function sportspress_the_title( $title, $id = null ) {
	if ( ! $id ) return $title;

	if ( ! is_admin() && in_the_loop() && $id == get_the_ID() ):
		if ( is_singular( 'sp_player' ) ):
			$number = get_post_meta( $id, 'sp_number', true );
			if ( $number != null ):
				$title = '<strong class="sp-player-number">' . $number . '</strong> ' . $title;
			endif;
		elseif ( is_singular( 'sp_staff' ) ):
			$staff = new SP_Staff( $id );
			$role = $staff->role();
			if ( $role )
				$title = '<strong class="sp-staff-role">' . $role->name . '</strong> ' . $title;
		endif;
	endif;

	return $title;
}
add_filter( 'the_title', 'sportspress_the_title', 10, 2 );

function sportspress_gettext( $translated_text, $untranslated_text, $domain = null ) {
	global $typenow;

	if ( is_admin() ):
		if ( is_sp_config_type( $typenow ) ):
			switch ( $untranslated_text ):
			case 'Excerpt':
				$translated_text = __( 'Description', 'sportspress' );
				break;
			case 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="http://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>':
				$translated_text = __( 'The description is not prominent by default; however, some themes may show it.', 'sportspress' );
				break;
			case 'Slug':
				$translated_text = ( in_array( $typenow, array( 'sp_column', 'sp_statistic' ) ) ) ? __( 'Key', 'sportspress' ) : __( 'Variable', 'sportspress' );
				break;
			endswitch;
		endif;

		if ( is_sp_post_type( $typenow ) ):
			switch ( $untranslated_text ):
			case 'Author':
				$translated_text = __( 'User', 'sportspress' );
				break;
			endswitch;
		endif;

		if ( in_array( $typenow, array( 'sp_event' ) ) ):
			switch ( $untranslated_text ):
			case 'Publish <b>immediately</b>':
				$translated_text = __( 'Date/Time:', 'sportspress' ) . ' <b>' . __( 'Now', 'sportspress' ) . '</b>';
				break;
			endswitch;
		endif;
	else:
		if ( $domain == 'sportspress' ):
			if ( ! empty( SP()->text[ $translated_text ] ) ):
				$translated_text = SP()->text[ $translated_text ];
			endif;
    	elseif ( ! current_theme_supports( 'sportspress' ) && $untranslated_text == 'Archives' && is_tax( 'sp_venue' ) ):
    		$slug = get_query_var( 'sp_venue' );
		    if ( $slug ):
			    $venue = get_term_by( 'slug', $slug, 'sp_venue' );
				$translated_text = $venue->name;
			endif;
		endif;
	endif;
	
	return $translated_text;
}
add_filter( 'gettext', 'sportspress_gettext', 20, 3 );

function sportspress_team_permalink( $permalink, $post ) {
    if ( ! is_admin() && 'sp_team' == get_post_type( $post ) ):

    	$url = get_post_meta( $post->ID, 'sp_url', true );

    	if ( ! empty( $url ) ):
	    	$redirect = get_post_meta( $post->ID, 'sp_redirect', true );

	    	if ( $redirect === '' ):
		    	$redirect = ( empty( $post->post_content ) ) ? 1 : 0;
		    endif;

		    if ( $redirect ):
		    	return $url;
		    endif;
	    endif;
    endif;
    return $permalink;
}
add_filter( 'post_type_link', 'sportspress_team_permalink', 10, 2 );

function sportspress_no_terms_links( $term_list, $taxonomy ) {

    if ( in_array( $taxonomy, array( 'sp_league', 'sp_season', 'sp_position', 'sp_role' ) ) )
        return wp_filter_nohtml_kses( $term_list );

    return $term_list;
}
add_filter( 'the_terms', 'sportspress_no_terms_links', 10, 2 );

function sportspress_strcmp_term_slug( $a, $b ) {
    return strcmp( $a->slug, $b->slug );
}

function sportspress_term_order( $terms, $post_id, $taxonomy ) {

	if ( ! is_array( $terms ) ) {
		return array();
	}

    if ( is_sp_taxonomy( $taxonomy ) ) {
    	uasort( $terms, 'sportspress_strcmp_term_slug' );
    }

    return $terms;
}
add_filter( 'get_the_terms', 'sportspress_term_order', 10, 3 );

function sportspress_pre_get_posts( $query ) {
	$post_type = sp_array_value( $query->query, 'post_type', null );

	if ( is_sp_post_type( $post_type ) ):
		$query->set( 'suppress_filters', 0 );
	endif;

	if ( is_admin() ):
		if ( isset( $query->query[ 'orderby' ] ) || isset( $query->query[ 'order' ] ) ):
			return $query;
		endif;

		if ( is_sp_config_type( $post_type ) ):
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		endif;
	else:
		if ( isset( $query->query[ 'sp_venue' ] ) ):
			$query->set( 'post_type', 'sp_event' );
			$GLOBALS[ 'wp_post_statuses' ][ 'future' ]->public = true;
		endif;
	endif;

	return $query;
}
add_filter('pre_get_posts', 'sportspress_pre_get_posts');

function sportspress_show_future_posts( $where, $that ) {
    global $wpdb;
    if( 'sp_event' == $that->query_vars['post_type'] && is_archive() )
        $where = str_replace( "{$wpdb->posts}.post_status = 'publish'", "{$wpdb->posts}.post_status = 'publish' OR $wpdb->posts.post_status = 'future'", $where );
    return $where;
}
add_filter( 'posts_where', 'sportspress_show_future_posts', 2, 10 );

function sportspress_redirect_future_events() {
	if ( is_main_query() && 'sp_event' == get_query_var( 'post_type' ) && 'future' == get_post_status( get_query_var( 'p' ) ) && ! empty( $_GET['p'] ) ) {
		if ( $redirect_url = get_post_permalink( get_query_var( 'p' ), false, true ) )
			wp_redirect( $redirect_url, 301 );
			die();
	}
}
add_action( 'template_redirect', 'sportspress_redirect_future_events' );

function sportspress_give_event_read_permissions( $allcaps, $caps, $args ) {

	// Bail out if we're not asking about viewing an event
	if ( 'read_sp_event' !== $args[0] )
		return $allcaps;

	// Load the post data
	$post = get_post( $args[2] );

	// Bail out if the event isn't scheduled
	if ( 'future' != $post->post_status )
		return $allcaps;

	// Add post capabilities
	foreach ( $caps as $cap ) {
		$allcaps[ $cap ] = true;
	}

	return $allcaps;
}
add_filter( 'user_has_cap', 'sportspress_give_event_read_permissions', 10, 3 );

function sportspress_sanitize_title( $title ) {

	if ( isset( $_POST ) && array_key_exists( 'taxonomy', $_POST ) ):

		return $title;
	
	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && is_sp_config_type( $_POST['post_type'] ) ):

		$key = isset( $_POST['sp_key'] ) ? $_POST['sp_key'] : null;

		if ( ! $key ) $key = isset( $_POST['sp_default_key'] ) ? $_POST['sp_default_key'] : null;

		if ( ! $key ) $key = $_POST['post_title'];

		$id = sp_array_value( $_POST, 'post_ID', 'var' );

		$title = sp_get_eos_safe_slug( $key, $id );

	endif;

	return $title;
}
add_filter( 'sanitize_title', 'sportspress_sanitize_title' );

function sportspress_widget_text( $content ) {
	if ( ! preg_match( '/\[[\r\n\t ]*(countdown|events?(_|-)(results|details|performance|calendar|list|blocks)|team(_|-)columns|league(_|-)table|player(_|-)(metrics|performance|list|gallery))?[\r\n\t ].*?\]/', $content ) )
		return $content;

	$content = do_shortcode( $content );

	return $content;
}
add_filter( 'widget_text', 'sportspress_widget_text', 9 );

function sportspress_post_updated_messages( $messages ) {

	global $typenow, $post;

	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_metric', 'sp_spec', 'sp_performance' ) ) ):
		$obj = get_post_type_object( $typenow );

		for ( $i = 0; $i <= 10; $i++ ):
			$messages['post'][ $i ] = __( 'Settings saved.', 'sportspress' ) .
				' <a href="' . esc_url( admin_url( 'edit.php?post_type=' . $typenow ) ) . '">' .
				__( 'View All', 'sportspress' ) . '</a>';
		endfor;

	elseif ( in_array( $typenow, array( 'sp_event', 'sp_team', 'sp_table', 'sp_player', 'sp_list', 'sp_staff' ) ) ):
		$obj = get_post_type_object( $typenow );

		$messages['post'][1] = __( 'Changes saved.', 'sportspress' ) .
			' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

		$messages['post'][4] = __( 'Changes saved.', 'sportspress' );

		$messages['post'][6] = __( 'Success!', 'sportspress' ) .
			' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

		$messages['post'][7] = __( 'Changes saved.', 'sportspress' );

		$messages['post'][8] = __( 'Success!', 'sportspress' ) .
			' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

		$messages['post'][9] = sprintf(
			__( 'Scheduled for: <b>%1$s</b>.', 'sportspress' ),
			date_i18n( __( 'M j, Y @ G:i', 'sportspress' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ) .
			' <a target="_blank" href="' . esc_url( get_permalink($post->ID) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

		$messages['post'][10] = __( 'Success!', 'sportspress' ) .
			' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
			sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

	endif;

	return $messages;
}

function sportspress_hide_adjacent_post_links( $output = null, $format = null, $link = null, $post = null ) {
	if ( is_object( $post ) && property_exists( $post, 'post_type' ) && in_array( $post->post_type, sp_post_types() ) )
		return false;
	return $output;
}

add_filter('post_updated_messages', 'sportspress_post_updated_messages');

function sportspress_remove_page_parent_class( $classes, $item ) {
	if ( ( is_sp_post_type( get_post_type() ) && $key = array_search( 'current_page_parent', $classes ) ) !== false ) {
	    unset( $classes[$key] );
	}
    return $classes;
}

add_filter( 'nav_menu_css_class', 'sportspress_remove_page_parent_class', 10, 2 );
