<?php
function sp_result_cpt_init() {
	$name = __( 'Results', 'sportspress' );
	$singular_name = __( 'Result', 'sportspress' );
	$lowercase_name = __( 'result', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_result_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_result', $args );
}
add_action( 'init', 'sp_result_cpt_init' );

function sp_result_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
		'sp_format' => __( 'Format', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_result_columns', 'sp_result_edit_columns' );

function sp_result_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sp_result_details_meta', 'sp_result', 'normal', 'high' );
}

function sp_result_details_meta( $post ) {
	$formats = sp_get_config_formats();
	?>
	<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
	</p>
	<p><strong><?php _e( 'Format', 'sportspress' ); ?></strong></p>
	<p class="sp-format-selector">
		<select name="sp_format">
			<?php
			foreach ( $formats as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $priority, false ), $value );
			endforeach;
			?>
		</select>
	</p>
	<?php
	sp_nonce();
}
