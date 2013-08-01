<?php
function sp_table_cpt_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_table_meta_init',
		'rewrite' => array( 'slug' => 'table' )
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sp_table_cpt_init' );

function sp_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sp_table_edit_columns' );

function sp_table_meta_init() {
	remove_meta_box( 'submitdiv', 'sp_table', 'side' );
	add_meta_box( 'submitdiv', __( 'Event', 'sportspress' ), 'post_submit_meta_box', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_table_team_meta', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'League Table', 'sportspress' ), 'sp_table_stats_meta', 'sp_table', 'normal', 'high' );
}

function sp_table_team_meta( $post ) {
	$league = get_post_meta( $post->ID, 'sp_league', true );
	?>
	<div>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Leagues', 'sportspress' ) ),
				'taxonomy' => 'sp_league',
				'name' => 'sportspress[sp_league]',
				'selected' => $league
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php
		sp_post_checklist( $post->ID, 'sp_team', 'block', 'sp_league' );
		sp_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_table_stats_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );
	$league = (int)get_post_meta( $post->ID, 'sp_league', true );
	$data = sp_array_combine( $teams, sp_array_value( $stats, $league, array() ) );
	$placeholders = array();
	foreach ( $teams as $team ):
		$placeholders[ $team ] = sp_get_stats( $team, 0, $league );
	endforeach;
	sp_stats_table( $data, $placeholders, $league, array( 'Team', 'P', 'W', 'D', 'L', 'F', 'A', 'GD', 'Pts' ), false );
}
?>