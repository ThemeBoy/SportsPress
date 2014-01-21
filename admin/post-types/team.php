<?php
function sportspress_team_post_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$lowercase_name = __( 'teams', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_team_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_team_slug', 'teams' ) ),
		'menu_icon' => 'dashicons-shield-alt',
		'capability_type' => 'sp_team'
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sportspress_team_post_init' );

function sportspress_team_meta_init( $post ) {
	$leagues = get_the_terms( $post->ID, 'sp_league' );
	$seasons = get_the_terms( $post->ID, 'sp_season' );

	remove_meta_box( 'submitdiv', 'sp_team', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_team', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_team', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'low' );

	if ( $leagues && $seasons ):
		add_meta_box( 'sp_columnssdiv', __( 'Columns', 'sportspress' ), 'sportspress_team_columns_meta', 'sp_team', 'normal', 'high' );
	endif;
}

function sportspress_team_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
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
