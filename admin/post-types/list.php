<?php
function sportspress_list_post_init() {
	$labels = array(
		'name' => __( 'Player Lists', 'sportspress' ),
		'singular_name' => __( 'Player List', 'sportspress' ),
		'add_new_item' => __( 'Add New Player List', 'sportspress' ),
		'edit_item' => __( 'Edit Player List', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Player Lists', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_list_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_list_slug', 'list' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_player',
		'show_in_admin_bar' => true,
		'capability_type' => 'sp_list'
	);
	register_post_type( 'sp_list', $args );
}
add_action( 'init', 'sportspress_list_post_init' );

function sportspress_list_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'sportspress' ),
		'sp_player' => __( 'Players', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_team' => __( 'Team', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sportspress_list_edit_columns' );

function sportspress_list_meta_init( $post ) {
	$players = (array)get_post_meta( $post->ID, 'sp_player', false );

	remove_meta_box( 'sp_seasondiv', 'sp_list', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_list', 'side' );
	add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'sportspress_list_shortcode_meta', 'sp_list', 'side', 'default' );
	add_meta_box( 'sp_formatdiv', __( 'Format', 'sportspress' ), 'sportspress_list_format_meta', 'sp_list', 'side', 'default' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_list_details_meta', 'sp_list', 'side', 'default' );
	add_meta_box( 'sp_statsdiv', __( 'Player List', 'sportspress' ), 'sportspress_list_stats_meta', 'sp_list', 'normal', 'default' );
	add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'sportspress_list_description_meta', 'sp_list', 'normal', 'high' );
}

function sportspress_list_shortcode_meta( $post ) {
	$the_format = get_post_meta( $post->ID, 'sp_format', true );
	?>
	<p class="howto">
		<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
	</p>
	<p><input type="text" value="[player-<?php echo $the_format; ?> <?php echo $post->ID; ?>]" readonly="readonly" class="wp-ui-text-highlight code"></p>
	<?php
}

function sportspress_list_format_meta( $post ) {
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

function sportspress_list_details_meta( $post ) {
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
			sportspress_post_adder( 'sp_list', __( 'Add New', 'sportspress' ) );
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
		<p><strong><?php _e( 'Players', 'sportspress' ); ?></strong></p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team' );
		sportspress_post_adder( 'sp_player', __( 'Add New', 'sportspress' ) );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_list_stats_meta( $post ) {

	list( $columns, $usecolumns, $data, $placeholders, $merged ) = sportspress_get_player_list_data( $post->ID, true );

	sportspress_edit_player_list_table( $columns, $usecolumns, $data, $placeholders );
	sportspress_nonce();
}

function sportspress_list_description_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
