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
		'hierarchical' => false,
		'supports' => array( 'title', 'author', 'thumbnail', 'page-attributes' ),
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
		'title' => __( 'Name', 'sportspress' ),
		'sp_position' => __( 'Positions', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_league' => __( 'Leagues', 'sportspress' ),
		'sp_season' => __( 'Seasons', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_player_columns', 'sportspress_player_edit_columns' );

function sportspress_player_meta_init( $post ) {
	$leagues = get_the_terms( $post->ID, 'sp_league' );
	$seasons = (array)get_the_terms( $post->ID, 'sp_season' );

	remove_meta_box( 'submitdiv', 'sp_player', 'side' );
	add_meta_box( 'submitdiv', __( 'Publish' ), 'post_submit_meta_box', 'sp_player', 'side', 'high' );
	remove_meta_box( 'postimagediv', 'sp_player', 'side' );
	add_meta_box( 'postimagediv', __( 'Photo', 'sportspress' ), 'post_thumbnail_meta_box', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_player_details_meta', 'sp_player', 'side', 'high' );
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sportspress_player_team_meta', 'sp_player', 'side', 'high' );

	if ( $leagues && ! empty( $leagues ) && $seasons && is_array( $seasons ) && is_object( $seasons[0] ) ):
		add_meta_box( 'sp_statsdiv', __( 'Player Statistics', 'sportspress' ), 'sportspress_player_stats_meta', 'sp_player', 'normal', 'high' );
	endif;

	add_meta_box( 'sp_metricsdiv', __( 'Player Metrics', 'sportspress' ), 'sportspress_player_metrics_meta', 'sp_player', 'normal', 'high' );
	add_meta_box( 'sp_profilediv', __( 'Profile' ), 'sportspress_player_profile_meta', 'sp_player', 'normal', 'high' );
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

		list( $columns, $data, $seasons_teams ) = sportspress_get_player_statistics_data( $post->ID, $league->term_id, true );

		sportspress_edit_player_statistics_table( $league->term_id, $columns, $data, $seasons_teams );

	endforeach;
}

function sportspress_player_metrics_meta( $post ) {
	$metrics = get_post_meta( $post->ID, 'sp_metrics', true );

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
