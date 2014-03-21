<?php
function sportspress_table_post_init() {
	$labels = array(
		'name' => __( 'League Tables', 'sportspress' ),
		'singular_name' => __( 'League Table', 'sportspress' ),
		'add_new_item' => __( 'Add New League Table', 'sportspress' ),
		'edit_item' => __( 'Edit League Table', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'League Tables', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_table_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_table_slug', 'table' ) ),
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
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sportspress_table_edit_columns' );

function sportspress_table_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	remove_meta_box( 'sp_seasondiv', 'sp_table', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_table', 'side' );
	add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'sportspress_table_shortcode_meta', 'sp_table', 'side', 'default' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_table_team_meta', 'sp_table', 'side', 'default' );
	add_meta_box( 'sp_columnsdiv', __( 'League Table', 'sportspress' ), 'sportspress_table_columns_meta', 'sp_table', 'normal', 'high' );
	add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'sportspress_table_description_meta', 'sp_table', 'normal', 'high' );
}

function sportspress_table_shortcode_meta( $post ) {
	?>
	<p class="howto">
		<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
	</p>
	<p><input type="text" value="[league-table <?php echo $post->ID; ?>]" readonly="readonly" class="wp-ui-text-highlight code"></p>
	<?php
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
				'values' => 'term_id'
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_league', 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Season', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'taxonomy' => 'sp_season',
				'name' => 'sp_season',
				'selected' => $season_id,
				'values' => 'term_id'
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Teams', 'sportspress' ); ?></strong></p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_team', 'block', 'sp_season' );
		sportspress_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_table_columns_meta( $post ) {

	list( $columns, $usecolumns, $data, $placeholders, $merged ) = sportspress_get_league_table_data( $post->ID, true );

	sportspress_edit_league_table( $columns, $usecolumns, $data, $placeholders );

	sportspress_nonce();
}

function sportspress_table_description_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
