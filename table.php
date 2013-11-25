<?php
function sp_table_cpt_init() {
	$name = __( 'League Tables', 'sportspress' );
	$singular_name = __( 'League Table', 'sportspress' );
	$lowercase_name = __( 'league tables', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => false,
		'supports' => array( 'title', 'author' ),
		'register_meta_box_cb' => 'sp_table_meta_init',
		'rewrite' => array( 'slug' => 'table' ),
		'show_in_menu' => 'edit.php?post_type=sp_team'
	);
	register_post_type( 'sp_table', $args );
}
add_action( 'init', 'sp_table_cpt_init' );

function sp_table_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Title' ),
		'sp_team' => __( 'Teams', 'sportspress' ),
		'sp_div' => __( 'Divisions', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_table_columns', 'sp_table_edit_columns' );

function sp_table_meta_init() {
	add_meta_box( 'sp_teamdiv', __( 'Teams', 'sportspress' ), 'sp_table_team_meta', 'sp_table', 'side', 'high' );
	add_meta_box( 'sp_statsdiv', __( 'League Table', 'sportspress' ), 'sp_table_stats_meta', 'sp_table', 'normal', 'high' );
}

function sp_table_team_meta( $post ) {
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
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
		<?php
		sp_post_checklist( $post->ID, 'sp_team', 'block', 'sp_div' );
		sp_post_adder( 'sp_team' );
		?>
	</div>
	<?php
	sp_nonce();
}

function sp_table_stats_meta( $post ) {
	$teams = (array)get_post_meta( $post->ID, 'sp_team', false );
	$stats = (array)get_post_meta( $post->ID, 'sp_stats', true );
	$division_id = sp_get_the_term_id( $post->ID, 'sp_div', 0 );
	$data = sp_array_combine( $teams, sp_array_value( $stats, $division_id, array() ) );

	// Generate array of placeholder values for each team
	$placeholders = array();
	foreach ( $teams as $team ):
		$args = array(
			'post_type' => 'sp_event',
			'meta_query' => array(
				array(
					'key' => 'sp_team',
					'value' => $team
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
		$placeholders[ $team ] = sp_get_stats_row( 'sp_team', $args, true );
	endforeach;

	// Get column names from settings
	$stats_settings = get_option( 'sportspress_stats' );
	$columns = sp_get_eos_keys( $stats_settings['team'] );

	// Add first column label
	array_unshift( $columns, __( 'Team', 'sportspress' ) );

	sp_stats_table( $data, $placeholders, $division_id, $columns, false );
}
?>