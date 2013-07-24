<?php
function sp_team_cpt_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$labels = sp_get_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_team_meta_init',
		'rewrite' => array( 'slug' => 'team' ),
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sp_team_cpt_init' );

function sp_team_text_replace( $input, $text, $domain ) {
	global $post;
	if ( is_admin() && get_post_type( $post ) == 'sp_team' )
		switch ( $text ):
			case 'Set featured image':
				return sprintf( __( 'Add %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
	    		break;
			case 'Set Featured Image':
				return sprintf( __( 'Add %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
	    		break;
			case 'Remove featured image':
				return sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) );
	    		break;
			default:
				return $input;
		endswitch;
	return $input;
}
add_filter( 'gettext', 'sp_team_text_replace', 20, 3 );

function sp_team_meta_init() {
	remove_meta_box( 'postimagediv', 'sp_team', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'high' );
	remove_meta_box( 'pageparentdiv', 'sp_team', 'side' );
	add_meta_box( 'pageparentdiv', __( 'Team', 'sportspress' ), 'page_attributes_meta_box', 'sp_team', 'side', 'high' );
}

function sp_team_edit_columns($columns) {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsor', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sp_team_edit_columns' );

function sp_team_custom_columns( $column ) {
	global $post, $post_id, $typenow;
	if ( $typenow == 'sp_team' ):
		switch ( $column ):
			case 'sp_icon':
				the_post_thumbnail( 'sp_icon' );
				break;
			case 'sp_league':
				if ( get_the_terms ( $post_id, 'sp_league' ) )
					the_terms( $post_id, 'sp_league' );
				else
					echo '—';
				break;
			case 'sp_season':
				if ( get_the_terms ( $post_id, 'sp_season' ) )
					the_terms( $post_id, 'sp_season' );
				else
					echo '—';
				break;
			case 'sp_sponsor':
				if ( get_the_terms ( $post_id, 'sp_sponsor' ) )
					the_terms( $post_id, 'sp_sponsor' );
				else
					echo '—';
				break;
		endswitch;
	endif;
}
add_action( 'manage_pages_custom_column', 'sp_team_custom_columns' );

function sp_team_request_filter_dropdowns() {
	global $typenow, $wp_query;
	if ( $typenow == 'sp_team' ) {

		// Leagues
		$selected = isset( $_REQUEST['sp_league'] ) ? $_REQUEST['sp_league'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
			'taxonomy' => 'sp_league',
			'name' => 'sp_league',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Seasons
		$selected = isset( $_REQUEST['sp_season'] ) ? $_REQUEST['sp_season'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Seasons', 'sportspress' ) ),
			'taxonomy' => 'sp_season',
			'name' => 'sp_season',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );
		echo PHP_EOL;

		// Sponsors
		$selected = isset( $_REQUEST['sp_sponsor'] ) ? $_REQUEST['sp_sponsor'] : null;
		$args = array(
			'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Sponsors', 'sportspress' ) ),
			'taxonomy' => 'sp_sponsor',
			'name' => 'sp_sponsor',
			'selected' => $selected
		);
		sp_dropdown_taxonomies( $args );

	}
}
add_action( 'restrict_manage_posts', 'sp_team_request_filter_dropdowns' );
?>