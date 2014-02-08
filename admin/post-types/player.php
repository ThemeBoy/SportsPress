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
	add_meta_box( 'submitdiv', __( 'Publish', 'sportspress' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'low' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_player_details_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_player_team_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_metricsdiv', __( 'Metrics', 'sportspress' ), 'sportspress_player_metrics_meta', 'sp_player', 'normal', 'high' );

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
	$teams = array_filter( get_post_meta( $post->ID, 'sp_team', false ) );
	$current_team = get_post_meta( $post->ID, 'sp_current_team', true );
	?>
		<p>
			<strong><?php _e( 'Number', 'sportspress' ); ?></strong>
		</p>
		<p>
			<input type="text" size="4" id="sp_number" name="sp_number" value="<?php echo $number; ?>">
		</p>
		<p>
			<strong><?php _e( 'Nationality', 'sportspress' ); ?></strong>
		</p>
		<p>
			<select id="sp_nationality" name="sp_nationality">
				<?php foreach ( $continents as $continent => $countries ): ?>
					<option value=""><?php _e( '-- Not set --', 'sportspress' ); ?></option>
					<optgroup label="<?php echo $continent; ?>">
						<?php foreach ( $countries as $code => $country ): ?>
							<option value="<?php echo $code; ?>" <?php selected ( $nationality, $code ); ?>>
								<?php echo $country; ?>
							</option>
						<?php endforeach; ?>
					</optgroup>
				<?php endforeach; ?>
			</select>
		</p>
		<?php if ( $teams ): ?>
		<p>
			<strong><?php _e( 'Current Team', 'sportspress' ); ?></strong>
		</p>
		<p>
			<select id="sp_current_team" name="sp_current_team">
				<?php foreach ( $teams as $team ): ?>
					<option value="<?php echo $team; ?>" <?php selected ( $current_team, $team ); ?>>
						<?php echo get_the_title( $team ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php endif; ?>
	<?php
}

function sportspress_player_team_meta( $post ) {
	sportspress_post_checklist( $post->ID, 'sp_team' );
	sportspress_post_adder( 'sp_team' );
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

function sportspress_player_metrics_meta( $post ) {
	$metrics = get_post_meta( $post->ID, 'sp_metrics', true );
	$positions = get_the_terms( $post->ID, 'sp_position' );

	?>
	<div class="sp-data-table-container">
		<table class="widefat sp-data-table">
			<thead>
				<tr>
					<th><?php _e( 'Metric', 'sportspress' ); ?></th>
					<th><?php _e( 'Value', 'sportspress' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php

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

				$i = 0;
				foreach ( $vars as $var ):
					?>
					<tr class="sp-row sp-post<?php if ( $i % 2 == 0 ) echo ' alternate'; ?>">
						<td>
							<?php echo $var->post_title; ?>
						</td>
						<?php
						$value = sportspress_array_value( $metrics, $var->post_name, '' );
						?>
						<td><input type="text" name="sp_metrics[<?php echo $var->post_name; ?>]" value="<?php echo $value; ?>" /></td>
					</tr>
					<?php
					$i++;
				endforeach;
				?>
			</tbody>
		</table>
	</div>
	<?php
	sportspress_nonce();
}

function sportspress_player_profile_meta( $post ) {
	wp_editor( $post->post_content, 'content' );
}
