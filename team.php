<?php
function sp_team_cpt_init() {
	$name = __( 'Teams', 'sportspress' );
	$singular_name = __( 'Team', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_team_meta_init',
		'rewrite' => array( 'slug' => 'team' )
	);
	register_post_type( 'sp_team', $args );
}
add_action( 'init', 'sp_team_cpt_init' );

function sp_team_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_team', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_team', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_team', 'side' );
	add_meta_box( 'postimagediv', __( 'Logo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_team', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'Statistics', 'sportspress' ), 'sp_team_stats_meta', 'sp_team', 'normal', 'high' );
}

function sp_team_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Team', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_sponsor' => __( 'Sponsors', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_team_columns', 'sp_team_edit_columns' );

function sp_team_stats_meta( $post ) {
	$leagues = (array)get_the_terms( $post->ID, 'sp_league' );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );

	$keys = array( 0 );
	foreach ( $leagues as $key => $value ):
		if ( is_object( $value ) && property_exists( $value, 'term_id' ) )
			$keys[] = $value->term_id;
	endforeach;

	$data = sp_array_combine( $keys, sp_array_value( $stats, 0, array() ) );
	?>
	<?php sp_data_table( $data, 0, array( 'Team', 'Played', 'Goals', 'Assists', 'Yellow Cards', 'Red Cards' ), true, true, 'sp_league' );

	sp_nonce();
}
?>