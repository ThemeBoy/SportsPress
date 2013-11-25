<?php
function sp_list_cpt_init() {
	$name = __( 'Player Lists', 'sportspress' );
	$singular_name = __( 'Player List', 'sportspress' );
	$lowercase_name = __( 'player lists', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author' ),
		'register_meta_box_cb' => 'sp_list_meta_init',
		'rewrite' => array( 'slug' => 'list' ),
		'show_in_menu' => 'edit.php?post_type=sp_player'
	);
	register_post_type( 'sp_list', $args );
}
add_action( 'init', 'sp_list_cpt_init' );

function sp_list_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_player' => __( 'Players', 'sportspress' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_list_columns', 'sp_list_edit_columns' );

function sp_list_meta_init() {
	add_meta_box( 'sp_playerdiv', __( 'Players', 'sportspress' ), 'sp_list_player_meta', 'sp_list', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'Player List', 'sportspress' ), 'sp_list_stats_meta', 'sp_list', 'normal', 'high' );
}

function sp_list_player_meta( $post ) {
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	?>
	<div>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'show_option_all' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Divisions', 'sportspress' ) ),
				'taxonomy' => 'sp_div',
				'name' => 'sp_div',
				'selected' => $division_id
			);
			sp_dropdown_taxonomies( $args );
			?>
		</p>
		<p class="sp-tab-select">
			<?php
			$args = array(
				'show_option_none' =>  sprintf( __( 'All %s', 'sportspress' ), __( 'Teams', 'sportspress' ) ),
				'option_none_value' => '0',
				'post_type' => 'sp_team',
				'name' => 'sp_team',
				'selected' => $team_id
			);
			wp_dropdown_pages( $args );
			?>
		</p>
		<?php
		sp_post_checklist( $post->ID, 'sp_player', 'block', 'sp_team' );
		sp_post_adder( 'sp_player' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_list_stats_meta( $post ) {
	$players = (array)get_post_meta( $post->ID, 'sp_player', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$team_id = get_post_meta( $post->ID, 'sp_team', true );
	$data = sp_array_combine( $players, sp_array_value( $stats, $division_id, array() ) );

	// Generate array of placeholder values for each player
	$placeholders = array();
	foreach ( $players as $player ):
		$args = array(
			'post_type' => 'sp_event',
			'meta_query' => array(
				array(
					'key' => 'sp_player',
					'value' => $player
				)
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'sp_div',
					'field' => 'id',
					'terms' => $division_id
				)
			)
		);
		$placeholders[ $player ] = sp_get_stats_row( 'sp_player', $args, true );
	endforeach;

	// Get column names from settings
	$stats_settings = get_option( 'sportspress_stats' );
	$columns = sp_get_eos_keys( $stats_settings['player'] );

	// Add first column label
	array_unshift( $columns, __( 'Player', 'sportspress' ) );

	sp_stats_table( $data, $placeholders, $division_id, $columns, false );
}
?>