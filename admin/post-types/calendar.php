<?php
function sportspress_calendar_post_init() {
	$name = __( 'Calendars', 'sportspress' );
	$singular_name = __( 'Calendar', 'sportspress' );
	$lowercase_name = __( 'calendars', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'excerpt' ),
		'register_meta_box_cb' => 'sportspress_calendar_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_calendar_slug', 'calendars' ) ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'show_in_admin_bar' => true,
//		'capability_type' => 'sp_calendar'
	);
	register_post_type( 'sp_calendar', $args );
}
add_action( 'init', 'sportspress_calendar_post_init' );

function sportspress_calendar_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_calendar_columns', 'sportspress_calendar_edit_columns' );

function sportspress_calendar_meta_init( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );

	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_calendar_team_meta', 'sp_calendar', 'side', 'high' );

	if ( $teams && $teams != array(0) ):
		add_meta_box( 'sp_columnsdiv', __( 'League Table', 'sportspress' ), 'sportspress_calendar_columns_meta', 'sp_calendar', 'normal', 'high' );
	endif;
}

function sportspress_calendar_team_meta( $post, $test ) {
	$league_id = sportspress_get_the_term_id( $post->ID, 'sp_season', 0 );
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
			sportspress_dropdown_taxonomies( $args );
			?>
		</p>
		<?php
		sportspress_post_checklist( $post->ID, 'sp_team', 'block', 'sp_season' );
		sportspress_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_calendar_columns_meta( $post ) {

//	list( $columns, $data, $placeholders, $merged ) = sportspress_get_league_calendar_data( $post->ID, true );

//	sportspress_edit_league_calendar( $columns, $data, $placeholders );

	sportspress_nonce();
}
