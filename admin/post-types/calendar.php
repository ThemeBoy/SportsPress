<?php
function sportspress_calendar_post_init() {
	$labels = array(
		'name' => __( 'Calendars', 'sportspress' ),
		'singular_name' => __( 'Calendar', 'sportspress' ),
		'add_new_item' => __( 'Add New Calendar', 'sportspress' ),
		'edit_item' => __( 'Edit Calendar', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Calendars', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail' ),
		'register_meta_box_cb' => 'sportspress_calendar_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sportspress_calendar_slug', 'calendar' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'show_in_admin_bar' => true,
		'capability_type' => 'sp_calendar'
	);
	register_post_type( 'sp_calendar', $args );
}
add_action( 'init', 'sportspress_calendar_post_init' );

function sportspress_calendar_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title', 'sportspress' ),
		'sp_league' => __( 'League', 'sportspress' ),
		'sp_season' => __( 'Season', 'sportspress' ),
		'sp_venue' => __( 'Venue', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_events' => __( 'Events', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_calendar_columns', 'sportspress_calendar_edit_columns' );

function sportspress_calendar_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	remove_meta_box( 'sp_seasondiv', 'sp_calendar', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_calendar', 'side' );
	remove_meta_box( 'sp_venuediv', 'sp_calendar', 'side' );
	add_meta_box( 'sp_shortcodediv', __( 'Shortcode', 'sportspress' ), 'sportspress_calendar_shortcode_meta', 'sp_calendar', 'side', 'default' );
	add_meta_box( 'sp_formatdiv', __( 'Format', 'sportspress' ), 'sportspress_calendar_format_meta', 'sp_calendar', 'side', 'default' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_calendar_details_meta', 'sp_calendar', 'side', 'default' );
	add_meta_box( 'sp_columnsdiv', __( 'Events', 'sportspress' ), 'sportspress_calendar_events_meta', 'sp_calendar', 'normal', 'high' );
	add_meta_box( 'sp_descriptiondiv', __( 'Description', 'sportspress' ), 'sportspress_calendar_description_meta', 'sp_calendar', 'normal', 'high' );
}

function sportspress_calendar_shortcode_meta( $post ) {
	$the_format = get_post_meta( $post->ID, 'sp_format', true );
	?>
	<p class="howto">
		<?php _e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
	</p>
	<p><input type="text" value="[events-<?php echo $the_format; ?> <?php echo $post->ID; ?>]" readonly="readonly" class="wp-ui-text-highlight code"></p>
	<?php
}

function sportspress_calendar_format_meta( $post ) {
	global $sportspress_formats;
	$the_format = get_post_meta( $post->ID, 'sp_format', true );
	?>
	<div id="post-formats-select">
		<?php foreach ( $sportspress_formats['calendar'] as $key => $format ): ?>
			<input type="radio" name="sp_format" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( true, ( $key == 'calendar' && ! $the_format ) || $the_format == $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $format; ?></label><br>
		<?php endforeach; ?>
	</div>
	<?php
}

function sportspress_calendar_details_meta( $post, $test ) {
	global $sportspress_formats;
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_league', 0 );
	$season_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
	$venue_id = sportspress_get_the_term_id( $post->ID, 'sp_venue', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	$formats = get_post_meta( $post->ID, 'sp_format' );
	?>
	<div>
		<p><strong><?php _e( 'League', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'show_option_all' => __( 'All', 'sportspress' ),
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
				'show_option_all' => __( 'All', 'sportspress' ),
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
		<p><strong><?php _e( 'Venue', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'show_option_all' => __( 'All', 'sportspress' ),
				'taxonomy' => 'sp_venue',
				'name' => 'sp_venue',
				'selected' => $venue_id,
				'values' => 'term_id'
			);
			if ( ! sportspress_dropdown_taxonomies( $args ) ):
				sportspress_taxonomy_adder( 'sp_season', 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
			?>
		</p>
		<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
		<p>
			<?php
			$args = array(
				'show_option_all' => __( 'All', 'sportspress' ),
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'selected' => $team_id,
				'values' => 'ID'
			);
			if ( ! sportspress_dropdown_pages( $args ) ):
				sportspress_post_adder( 'sp_team', __( 'Add New', 'sportspress' )  );
			endif;
			?>
		</p>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_calendar_events_meta( $post ) {

	list( $data, $usecolumns ) = sportspress_get_calendar_data( $post->ID, true );

	sportspress_edit_calendar_table( $data, $usecolumns );

	sportspress_nonce();

}

function sportspress_calendar_description_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
