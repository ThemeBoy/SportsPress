<?php
function sportspress_player_post_init() {
	$name = __( 'Players', 'sportspress' );
	$singular_name = __( 'Player', 'sportspress' );
	$lowercase_name = __( 'players', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'excerpt', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_player_meta_init',
		'rewrite' => array( 'slug' => get_option( 'sp_player_slug', 'players' ) ),
		'menu_icon' => 'dashicons-groups',
		'capability_type' => 'sp_player',
	);
	register_post_type( 'sp_player', $args );
}
add_action( 'init', 'sportspress_player_post_init' );

function sportspress_player_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'sp_icon' => '&nbsp;',
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
		'sp_views' => __( 'Views', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sportspress_player_edit_columns' );

function sportspress_player_meta_init( $post ) {
	$leagues = get_the_terms( $post->ID, 'sp_league' );
	$seasons = (array)get_the_terms( $post->ID, 'sp_season' );

	remove_meta_box( 'submitdiv', 'sp_player', 'side' );
	remove_meta_box( 'sp_seasondiv', 'sp_player', 'side' );
	remove_meta_box( 'sp_leaguediv', 'sp_player', 'side' );
	remove_meta_box( 'sp_positiondiv', 'sp_player', 'side' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );

	add_meta_box( 'submitdiv', __( 'Publish', 'sportspress' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_player_details_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_metricsdiv', __( 'Metrics', 'sportspress' ), 'sportspress_player_metrics_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'low' );

	if ( $leagues && ! empty( $leagues ) && $seasons && ! empty( $seasons ) ):
		add_meta_box( 'sp_statsdiv', __( 'Statistics', 'sportspress' ), 'sportspress_player_stats_meta', 'sp_player', 'normal', 'high' );
	endif;

	add_meta_box( 'sp_profilediv', __( 'Profile', 'sportspress' ), 'sportspress_player_profile_meta', 'sp_player', 'normal', 'high' );
}

function sportspress_player_details_meta( $post ) {
	global $sportspress_continents, $sportspress_countries;

	$continents = array();

	foreach( $sportspress_continents as $continent => $codes ):
		$countries = array_intersect_key( $sportspress_countries, array_flip( $codes ) );
		asort( $countries );
		$continents[ $continent ] = $countries;
	endforeach;

	$number = get_post_meta( $post->ID, 'sp_number', true );
	$nationality = get_post_meta( $post->ID, 'sp_nationality', true );

	$leagues = get_the_terms( $post->ID, 'sp_league' );
	$league_ids = array();
	if ( $leagues ):
		foreach ( $leagues as $league ):
			$league_ids[] = $league->term_id;
		endforeach;
	endif;

	$seasons = get_the_terms( $post->ID, 'sp_season' );
	$season_ids = array();
	if ( $seasons ):
		foreach ( $seasons as $season ):
			$season_ids[] = $season->term_id;
		endforeach;
	endif;

	$positions = get_the_terms( $post->ID, 'sp_position' );
	$position_ids = array();
	if ( $positions ):
		foreach ( $positions as $position ):
			$position_ids[] = $position->term_id;
		endforeach;
	endif;
	
	$teams = get_posts( array( 'post_type' => 'sp_team', 'posts_per_page' => -1 ) );
	$the_teams = array_filter( get_post_meta( $post->ID, 'sp_team', false ) );
	$current_team = get_post_meta( $post->ID, 'sp_current_team', true );
	?>
	<p><strong><?php _e( 'Number', 'sportspress' ); ?></strong></p>
	<p><input type="text" size="4" id="sp_number" name="sp_number" value="<?php echo $number; ?>"></p>

	<p><strong><?php _e( 'Nationality', 'sportspress' ); ?></strong></p>
	<p><select id="sp_nationality" name="sp_nationality" data-placeholder="<?php _e( 'Select Nationality', 'sportspress' ); ?>" class="widefat chosen-select<?php if ( is_rtl() ): ?> chosen-rtl<?php endif; ?>">
		<option value=""></option>
		<?php foreach ( $continents as $continent => $countries ): ?>
			<optgroup label="<?php echo $continent; ?>">
				<?php foreach ( $countries as $code => $country ): ?>
					<option value="<?php echo $code; ?>" <?php selected ( $nationality, $code ); ?>><?php echo $country; ?></option>
				<?php endforeach; ?>
			</optgroup>
		<?php endforeach; ?>
	</select></p>

	<p><strong><?php _e( 'Leagues', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'taxonomy' => 'sp_league',
		'name' => 'tax_input[sp_league][]',
		'selected' => $league_ids,
		'values' => 'term_id',
		'placeholder' => __( 'Select Leagues', 'sportspress' ),
		'class' => 'widefat',
		'property' => 'multiple',
		'chosen' => true,
	);
	sportspress_dropdown_taxonomies( $args );
	?></p>

	<p><strong><?php _e( 'Seasons', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'taxonomy' => 'sp_season',
		'name' => 'tax_input[sp_season][]',
		'selected' => $season_ids,
		'values' => 'term_id',
		'placeholder' => __( 'Select Seasons', 'sportspress' ),
		'class' => 'widefat',
		'property' => 'multiple',
		'chosen' => true,
	);
	sportspress_dropdown_taxonomies( $args );
	?></p>

	<p><strong><?php _e( 'Teams', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'post_type' => 'sp_team',
		'name' => 'sp_team[]',
		'selected' => $the_teams,
		'values' => 'ID',
		'placeholder' => __( 'Select Teams', 'sportspress' ),
		'class' => 'sp-team widefat',
		'property' => 'multiple',
		'chosen' => true,
	);
	sportspress_dropdown_pages( $args );
	?></p>

	<p><strong><?php _e( 'Current Team', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'post_type' => 'sp_team',
		'name' => 'sp_current_team',
		'show_option_blank' => true,
		'selected' => $current_team,
		'values' => 'ID',
		'placeholder' => __( 'Select Team', 'sportspress' ),
		'class' => 'sp-current-team widefat',
		'chosen' => true,
	);
	sportspress_dropdown_pages( $args );
	?></p>

	<p><strong><?php _e( 'Positions', 'sportspress' ); ?></strong></p>
	<p><?php
	$args = array(
		'taxonomy' => 'sp_position',
		'name' => 'tax_input[sp_position][]',
		'selected' => $position_ids,
		'values' => 'term_id',
		'placeholder' => __( 'Select Positions', 'sportspress' ),
		'class' => 'widefat',
		'property' => 'multiple',
		'chosen' => true,
	);
	sportspress_dropdown_taxonomies( $args );
	?></p>
	<?php
}

function sportspress_player_metrics_meta( $post ) {
	$metrics = get_post_meta( $post->ID, 'sp_metrics', true );
	$positions = get_the_terms( $post->ID, 'sp_position' );

	$args = array(
		'post_type' => 'sp_metric',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC',
	);

	if ( $positions ):
		$position_ids = array();
		foreach( $positions as $position ):
			$position_ids[] = $position->term_id;
		endforeach;
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'sp_position',
				'field' => 'id',
				'terms' => $position_ids,
			),
		);
	endif;

	$vars = get_posts( $args );

	if ( $vars ):
		foreach ( $vars as $var ):
		?>
		<p><strong><?php echo $var->post_title; ?></strong></p>
		<p><input type="text" name="sp_metrics[<?php echo $var->post_name; ?>]" value="<?php echo sportspress_array_value( $metrics, $var->post_name, '' ); ?>" /></p>
		<?php
		endforeach;
	else:
		sportspress_post_adder( 'sp_metric' );
	endif;
	
	sportspress_nonce();
}

function sportspress_player_stats_meta( $post ) {
	$leagues = get_the_terms( $post->ID, 'sp_league' );

	$league_num = sizeof( $leagues );

	// Loop through statistics for each league
	foreach ( $leagues as $league ):
		
		if ( $league_num > 1 ):
			?>
			<p><strong><?php echo $league->name; ?></strong></p>
			<?php
		endif;

		list( $columns, $data, $placeholders, $merged, $seasons_teams ) = sportspress_get_player_statistics_data( $post->ID, $league->term_id, true );

		sportspress_edit_player_statistics_table( $post->ID, $league->term_id, $columns, $data, $placeholders, $merged, $seasons_teams, ! current_user_can( 'edit_sp_teams' ) );

	endforeach;
}

function sportspress_player_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
