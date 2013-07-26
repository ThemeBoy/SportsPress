<?php
function sp_event_cpt_init() {
	$name = __( 'Events', 'sportspress' );
	$singular_name = __( 'Event', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'comments', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_event_meta_init',
		'rewrite' => array( 'slug' => 'event' )
	);
	register_post_type( 'sp_event', $args );
}
add_action( 'init', 'sp_event_cpt_init' );

function sp_event_display_scheduled( $posts ) {
	global $wp_query, $wpdb;
	if ( is_single() && $wp_query->post_count == 0 && isset( $wp_query->query_vars['sp_event'] )) {
		$posts = $wpdb->get_results( $wp_query->request );
	}
	return $posts;
}
add_filter( 'the_posts', 'sp_event_display_scheduled' );

function sp_event_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_event', 'side' );
	add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_event', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_event_team_meta', 'sp_event', 'normal', 'high' );
	add_meta_box( 'sp_articlediv', __( 'Article', 'sportspress' ), 'sp_event_article_meta', 'sp_event', 'normal', 'high' );
}

function sp_event_team_meta( $post ) {
	$limit = get_option( 'sp_event_team_count' );
	for ( $i = 0; $i < $limit; $i++ ):
		$selected = array_pad( array_slice( (array)get_post_meta( $post->ID, 'sp_team', false ), 0, $limit ), $limit, 0);
		$args = array(
			'post_type' => 'sp_team',
			'name' => 'sportspress[sp_team][]',
			'selected' => $selected[ $i ]
		);
		wp_dropdown_pages( $args );
	endfor;
	sp_post_checklist( $post->ID, 'sp_player', true );
	sp_nonce();
}

function sp_event_article_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}

function sp_event_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Event', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' ),
		'sp_kickoff' => __( 'Kick-off', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_event_columns', 'sp_event_edit_columns' );

function sp_event_edit_sortable_columns( $columns ) {
	$columns['sp_kickoff'] = 'sp_kickoff';
	return $columns;
}
add_filter( 'manage_edit-sp_event_sortable_columns', 'sp_event_edit_sortable_columns' );
?>