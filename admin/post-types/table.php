<?php
function sportspress_table_post_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$lowercase_name = __( 'league tables', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'excerpt' ),
		'register_meta_box_cb' => 'sportspress_table_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_table_slug', 'tables' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_team',
		'show_in_admin_bar' => true,
		'capability_type' => 'sp_table'
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sportspress_table_post_init' );

function sportspress_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sportspress_table_edit_columns' );

function sportspress_table_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	remove_meta_box( 'sp_seasondiv', 'sp_table', 'side' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_table_team_meta', 'sp_table', 'side', 'high' );

	if ( $teams && $teams != array(0) ):
		add_meta_box( 'sp_columnsdiv', __( 'League Table', 'sportspress' ), 'sportspress_table_columns_meta', 'sp_table', 'normal', 'high' );
	endif;
}

function sportspress_table_team_meta( $post, $test ) {
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_league', 0 );
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	?>
	<div>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_league',
				'name' => 'sp_league',
				'selected' => $league_id,
				'value' => 'term_id'
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $season_id,
				'value' => 'term_id'
			);
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<p><strong><?php _e( 'Teams', 'sportspress' ); ?></strong></p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_team', 'block', 'sp_season' );
		sportspress_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_table_columns_meta( $post ) {

	list( $columns, $data, $placeholders, $merged ) = sportspress_get_league_table_data( $post->ID, true );

	sportspress_edit_league_table( $columns, $data, $placeholders );

	sportspress_nonce();
}
