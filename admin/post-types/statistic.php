<?php
function sportspress_statistic_post_init() {
	$labels = array(
		'name' => __( 'Statistics', 'sportspress' ),
		'singular_name' => __( 'Statistic', 'sportspress' ),
		'add_new_item' => __( 'Add New Statistic', 'sportspress' ),
		'edit_item' => __( 'Edit Statistic', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Statistics', 'sportspress' ),
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_statistic_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_statistic', $args );
}
add_action( 'init', 'sportspress_statistic_post_init' );

function sportspress_statistic_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_positions' => __( 'Positions', 'sportspress' ),
		'sp_calculate' => __( 'Calculate', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_statistic_columns', 'sportspress_statistic_edit_columns' );

function sportspress_statistic_meta_init() {
	add_meta_box( 'sp_equationdiv', __( 'Details', 'sportspress' ), 'sportspress_statistic_equation_meta', 'sp_statistic', 'normal', 'high' );
}

function sportspress_statistic_equation_meta( $post ) {
	$calculate = get_post_meta( $post->ID, 'sp_calculate', true );
	?>
	<p><strong><?php _e( 'Calculate', 'sportspress' ); ?></strong></p>
	<p class="sp-calculate-selector">
		<?php sportspress_calculate_selector( $post->ID, $calculate ); ?>
	</p>
	<?php
	sportspress_nonce();
}
