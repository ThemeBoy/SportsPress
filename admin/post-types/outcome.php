<?php
function sportspress_outcome_post_init() {
	$labels = array(
		'name' => __( 'Outcomes', 'sportspress' ),
		'singular_name' => __( 'Outcome', 'sportspress' ),
		'add_new_item' => __( 'Add New Outcome', 'sportspress' ),
		'edit_item' => __( 'Edit Outcome', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Outcomes', 'sportspress' ),
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_outcome_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_outcome', $args );
}
add_action( 'init', 'sportspress_outcome_post_init' );

function sportspress_outcome_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_outcome_columns', 'sportspress_outcome_edit_columns' );

function sportspress_outcome_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_outcome_details_meta', 'sp_outcome', 'normal', 'high' );
}

function sportspress_outcome_details_meta( $post ) {
?>
	<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
	</p>
	<?php
	sportspress_nonce();
}
