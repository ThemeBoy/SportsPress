<?php
function sportspress_result_post_init() {
	$name = __( 'Results', 'sportspress' );
	$singular_name = __( 'Result', 'sportspress' );
	$lowercase_name = __( 'result', 'sportspress' );
	$labels = sportspress_get_post_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_result_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_result', $args );
}
add_action( 'init', 'sportspress_result_post_init' );

function sportspress_result_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_result_columns', 'sportspress_result_edit_columns' );

function sportspress_result_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_result_details_meta', 'sp_result', 'normal', 'high' );
}

function sportspress_result_details_meta( $post ) {
	$formats = sportspress_get_config_formats();
	?>
	<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
	</p>
	<?php
	sportspress_nonce();
}
