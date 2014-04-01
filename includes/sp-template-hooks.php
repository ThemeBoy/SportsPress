<?php
/**
 * SportsPress Template
 *
 * Functions for the templating system.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Functions
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function sportspress_default_venue_content( $query ) {
    if ( ! is_tax( 'sp_venue' ) )
        return;

    $slug = sp_array_value( $query->query, 'sp_venue', null );

    if ( ! $slug )
        return;

    $venue = get_term_by( 'slug', $slug, 'sp_venue' );
    $t_id = $venue->term_id;
    $venue_meta = get_option( "taxonomy_$t_id" );
    $address = sp_array_value( $venue_meta, 'sp_address', null );
    $latitude = sp_array_value( $venue_meta, 'sp_latitude', null );
    $longitude = sp_array_value( $venue_meta, 'sp_longitude', null );

    if ( $latitude != null && $longitude != null )
        echo '<div class="sp-google-map sp-venue-map" data-address="' . $address . '" data-latitude="' . $latitude . '" data-longitude="' . $longitude . '"></div>';
}
add_action( 'loop_start', 'sportspress_default_venue_content' );

function sportspress_the_title( $title, $id ) {
	if ( is_singular( 'sp_player' ) && in_the_loop() && $id == get_the_ID() ):
		$number = get_post_meta( $id, 'sp_number', true );
		if ( $number != null ):
			$title = '<strong>' . $number . '</strong> ' . $title;
		endif;
	endif;
	return $title;
}
add_filter( 'the_title', 'sportspress_the_title', 10, 2 );

/**
 * sportspress_admin_install_notices function.
 *
 * @access public
 * @return void
 */
function sportspress_admin_install_notices() {
	include( dirname( SP_PLUGIN_FILE ) . '/includes/admin/views/notice-install.php' );
}

/**
 * sportspress_theme_check_notice function.
 *
 * @access public
 * @return void
 */
function sportspress_theme_check_notice() {
//	include( dirname( SP_PLUGIN_FILE ) . '/includes/admin/views/notice-theme-support.php' );
}

function sportspress_gettext( $translated_text, $untranslated_text, $domain ) {
	global $typenow;

	if ( is_admin() ):
		if ( in_array( $typenow, array( 'sp_event', 'sp_team', 'sp_player', 'sp_staff' ) ) ):
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
    	if ( $untranslated_text == 'Archives' && is_tax( 'sp_venue' ) ):
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

function sportspress_pre_get_posts( $query ) {

	if ( is_admin() ):
		if ( isset( $query->query[ 'orderby' ] ) || isset( $query->query[ 'order' ] ) ):
			return $query;
		endif;
		$post_type = $query->query['post_type'];

		if ( in_array( $post_type, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance' ) ) ):
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		elseif ( $post_type == 'sp_event' ):
			$query->set( 'orderby', 'post_date' );
			$query->set( 'order', 'ASC' );
		endif;
	else:
		$post_type = $query->get( 'post_type' );
		if ( $query->is_post_type_archive && $post_type == 'sp_event' ):
			$query->set( 'order' , 'ASC' );
		endif;
	endif;

	return $query;
}
add_filter('pre_get_posts', 'sportspress_pre_get_posts');

function sp_posts_where( $where, $that ) {
    global $wpdb;
    if( 'sp_event' == $that->query_vars['post_type'] && is_archive() )
        $where = str_replace( "{$wpdb->posts}.post_status = 'publish'", "{$wpdb->posts}.post_status = 'publish' OR $wpdb->posts.post_status = 'future'", $where );
    return $where;
}
add_filter( 'posts_where', 'sp_posts_where', 2, 10 );

function sportspress_sanitize_title( $title ) {

	if ( isset( $_POST ) && array_key_exists( 'taxonomy', $_POST ) ):

		return $title;
	
	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && in_array( $_POST['post_type'], array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_performance', 'sp_metric' ) ) ):

		$key = isset( $_POST['sp_key'] ) ? $_POST['sp_key'] : null;

		if ( ! $key ) $key = $_POST['post_title'];

		$id = sp_array_value( $_POST, 'post_ID', 'var' );

		$title = sp_get_eos_safe_slug( $key, $id );

	elseif ( isset( $_POST ) && array_key_exists( 'post_type', $_POST ) && $_POST['post_type'] == 'sp_event' ):

		// Auto slug generation
		if ( $_POST['post_title'] == '' && ( $_POST['post_name'] == '' || is_int( $_POST['post_name'] ) ) ):

			$title = '';

		endif;

	endif;

	return $title;
}
add_filter( 'sanitize_title', 'sportspress_sanitize_title' );

function sportspress_the_content( $content ) {
    if ( is_single() || is_page() )
        sp_set_post_views( get_the_ID() );
    return $content;
}
add_filter( 'the_content', 'sportspress_the_content' );
add_filter( 'get_the_content', 'sportspress_the_content' );

function sportspress_default_event_content( $content ) {
    if ( is_singular( 'sp_event' ) && in_the_loop() ):
        $id = get_the_ID();

        // Video
        $video_url = get_post_meta( $id, 'sp_video', true );
        if ( $video_url ):
            global $wp_embed;
            echo $wp_embed->autoembed( $video_url );
        endif;

        // Results
        sp_get_template( 'event-results.php' );

        // Details
        sp_get_template( 'event-details.php' );

        // Venue
        sp_get_template( 'event-venue.php' );

        // Performance
        sp_get_template( 'event-performance.php' );

        // Staff
        sp_get_template( 'event-staff.php' );
    endif;

    return $content;
}
add_filter( 'the_content', 'sportspress_default_event_content', 7 );

function sportspress_default_calendar_content( $content ) {
    if ( is_singular( 'sp_calendar' ) && in_the_loop() ):
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        switch ( $format ):
            case 'list':
                sp_get_template( 'event-list.php', array(
                    'id' => $id
                ) );
                break;
            default:
                sp_get_template( 'event-calendar.php', array(
                    'id' => $id,
                    'initial' => false
                ) );
                break;
            endswitch;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_calendar_content' );

function sportspress_default_team_content( $content ) {
    if ( is_singular( 'sp_team' ) && in_the_loop() ):
        sp_get_template( 'team-columns.php' );
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_team_content' );

function sportspress_default_table_content( $content ) {
    if ( is_singular( 'sp_table' ) && in_the_loop() ):
        $id = get_the_ID();
        $leagues = get_the_terms( $id, 'sp_league' );
        $seasons = get_the_terms( $id, 'sp_season' );
        $terms = array();
        if ( $leagues ):
            $league = reset( $leagues );
            $terms[] = $league->name;
        endif;
        if ( $seasons ):
            $season = reset( $seasons );
            $terms[] = $season->name;
        endif;
        $title = '';
        if ( sizeof( $terms ) )
            echo '<h4 class="sp-table-caption">' . implode( ' &mdash; ', $terms ) . '</h4>';

        sp_get_template( 'league-table.php' );
        $excerpt = has_excerpt() ? wpautop( get_the_excerpt() ) : '';
        $content = $content . $excerpt;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_table_content' );

function sportspress_default_player_content( $content ) {
    if ( is_singular( 'sp_player' ) && in_the_loop() ):
        sp_get_template( 'player.php' );
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_player_content' );

function sportspress_default_list_content( $content ) {
    if ( is_singular( 'sp_list' ) && in_the_loop() ):
        $id = get_the_ID();
        $format = get_post_meta( $id, 'sp_format', true );
        switch ( $format ):
            case 'gallery':
                sp_get_template( 'player-gallery.php' );
                break;
            default:
                sp_get_template( 'player-list.php' );
                break;
            endswitch;
    endif;
    return $content;
}
add_filter( 'the_content', 'sportspress_default_list_content' );

function sportspress_widget_text( $content ) {
	if ( ! preg_match( '/\[[\r\n\t ]*(countdown|league_table|event(s)_(calendar|list)|player_(list|gallery))?[\r\n\t ].*?\]/', $content ) )
		return $content;

	$content = do_shortcode( $content );

	return $content;
}
add_filter( 'widget_text', 'sportspress_widget_text', 9 );

function sportspress_post_updated_messages( $messages ) {

	global $typenow, $post;

	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_metric', 'sp_performance' ) ) ):
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

add_filter('post_updated_messages', 'sportspress_post_updated_messages');

