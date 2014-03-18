<?php
function sportspress_directory_post_init() {
	$labels = array(
		'name' => __( 'Directories', 'sportspress' ),
		'singular_name' => __( 'Directory', 'sportspress' ),
		'add_new_item' => __( 'Add New Directory', 'sportspress' ),
		'edit_item' => __( 'Edit Directory', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Directories', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_directory_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_directory_slug', 'directory' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_player',
		'show_in_admin_bar' => true,
		'capability_type' => 'sp_directory'
	);
	register_post_type( 'sp_directory', $args );
}
add_action( 'init', 'sportspress_directory_post_init' );

function sportspress_directory_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'sportspress' ),
		'sp_staff' => __( 'Staff', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_team' => __( 'Team', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_directory_columns', 'sportspress_directory_edit_columns' );

function sportspress_directory_meta_init( $post ) {
	$players = (array)get_post_meta( $post->ID, 'sp_staff', false );

	remove_meta_box( 'sp_seasondiv', 'sp_directory', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_directory', 'side' );
	add_meta_box( 'sp_formatdiv', __( 'Format', 'sportspress' ), 'sportspress_directory_format_meta', 'sp_directory', 'side', 'high' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_directory_details_meta', 'sp_directory', 'side', 'high' );
	//add_meta_box( 'sp_columnsdiv', __( 'Staff List', 'sportspress' ), 'sportspress_directory_columns_meta', 'sp_directory', 'normal', 'high' );
	add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'sportspress_directory_description_meta', 'sp_directory', 'normal', 'high' );
}

function sportspress_directory_format_meta( $post ) {
	global $sportspress_formats;
	$the_format = get_post_meta( $post->ID, 'sp_format', true );
	?>
	<div id="post-formats-select">
		<?php foreach ( $sportspress_formats['list'] as $key => $format ): ?>
			<input type="radio" name="sp_format" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( true, ( $key == 'list' && ! $the_format ) || $the_format == $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $format; ?></label><br>
		<?php endforeach; ?>
	</div>
	<?php
}

function sportspress_directory_details_meta( $post ) {
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_league', 0 );
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	$orderby = get_post_meta( $post->ID, 'sp_orderby', true );
	$order = get_post_meta( $post->ID, 'sp_order', true );
	?>
	<div>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'taxonomy' => 'sp_league',
				'name' => 'sp_league',
				'selected' => $league_id,
				'values' => 'term_id',
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
				'values' => 'term_id',
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'show_option_all' => __( 'All', 'sportspress' ),
				'selected' => $team_id,
				'values' => 'ID',
			);
			if ( ! sportspress_dropdown_pages( $args ) ):
				sportspress_post_adder( 'sp_team', __( 'Add New', 'sportspress' ) );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Sort by:', 'sportspress' ); ?></strong></p>
		<p>
		<?php
		$args = array(
			'prepend_options' => array(
				'number' => __( 'Number', 'sportspress' ),
				'name' => __( 'Name', 'sportspress' ),
				'eventsplayed' => __( 'Played', 'sportspress' )
			),
			'post_type' => 'sp_statistic',
			'name' => 'sp_orderby',
			'selected' => $orderby,
			'values' => 'slug',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_directory', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>
		<p><strong><?php _e( 'Sort Order:', 'sportspress' ); ?></strong></p>
		<p>
			<select name="sp_order">
				<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
				<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
			</select>
		</p>
		<p><strong><?php _e( 'Staff', 'sportspress' ); ?></strong></p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_staff', 'block', 'sp_team' );
		sportspress_post_adder( 'sp_staff', __( 'Add New', 'sportspress' ) );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_directory_columns_meta( $post ) {

	list( $columns, $usecolumns, $data, $placeholders, $merged ) = sportspress_get_player_list_data( $post->ID, true );

	sportspress_edit_player_list_table( $columns, $usecolumns, $data, $placeholders );
	sportspress_nonce();
}

function sportspress_directory_description_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
