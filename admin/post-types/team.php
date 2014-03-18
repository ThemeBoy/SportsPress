<?php
function sportspress_team_post_init() {
	$labels = array(
		'name' => __( 'Teams', 'sportspress' ),
		'singular_name' => __( 'Team', 'sportspress' ),
		'add_new_item' => __( 'Add New Team', 'sportspress' ),
		'edit_item' => __( 'Edit Team', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
		'parent_item_colon' => __( 'Parent:', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Teams', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_team_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_teams_slug', 'teams' ) ),
		'menu_icon' => 'dashicons-shield-alt',
		'capability_type' => 'sp_team'
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sportspress_team_post_init' );

function sportspress_team_meta_init( $post ) {
	$leagues = get_the_terms( $post->ID, 'sp_league' );
	$seasons = get_the_terms( $post->ID, 'sp_season' );

	if ( $leagues && $seasons ):
		add_meta_box( 'sp_columnssdiv', __( 'Columns', 'sportspress' ), 'sportspress_team_columns_meta', 'sp_team', 'normal', 'high' );
	endif;
}

function sportspress_team_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '<span class="dashicons sp-icon-shield tips" title="' . __( 'Logo', 'sportspress' ) . '"></span>',
		'title' => __( 'Team', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sportspress_team_edit_columns' );

function sportspress_team_columns_meta( $post ) {
	$leagues = (array)get_the_terms( $post->ID, 'sp_league' );

	$league_num = sizeof( $leagues );

	// Loop through statistics for each league
	foreach ( $leagues as $league ):

		$league_id = $league->term_id;
		
		if ( $league_num > 1 ):
			?>
			<p><strong><?php echo $league->name; ?></strong></p>
			<?php
		endif;

		list( $columns, $data, $placeholders, $merged, $leagues_seasons ) = sportspress_get_team_columns_data( $post->ID, $league_id, true );

		sportspress_edit_team_columns_table( $league_id, $columns, $data, $placeholders, $merged, $leagues_seasons, ! current_user_can( 'edit_sp_tables' ) );

	endforeach;

	sportspress_nonce();
}
