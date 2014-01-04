<?php
function sp_table_cpt_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$lowercase_name = __( 'league tables', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'excerpt' ),
		'register_meta_box_cb' => 'sp_table_meta_init',
		'rewrite' => array( 'slug' => 'table' ),
		'show_in_menu' => 'edit.php?post_type=sp_team',
//		'capability_type' => 'sp_table'
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sp_table_cpt_init' );

function sp_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sp_table_edit_columns' );

function sp_table_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_table_team_meta', 'sp_table', 'side', 'high' );

	if ( $teams && $teams != array(0) ):
		add_meta_box( 'sp_columnsdiv', __( 'League Table', 'sportspress' ), 'sp_table_columns_meta', 'sp_table', 'normal', 'high' );
	endif;
}

function sp_table_team_meta( $post, $test ) {
	$league_id = sp_get_the_term_id( $post->ID, 'sp_season', 0 );
	?>
	<div>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $league_id,
				'value' => 'term_id'
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<?php
		sp_post_checklist( $post->ID, 'sp_team', 'block', 'sp_season' );
		sp_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_table_columns_meta( $post ) {

	list( $columns, $data, $placeholders, $merged ) = sp_get_table( $post->ID, true );

	sp_league_table( $columns, $data, $placeholders );

	sp_nonce();
}
?>