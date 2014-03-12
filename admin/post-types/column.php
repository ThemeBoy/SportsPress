<?php
function sportspress_column_post_init() {
	$labels = array(
		'name' => __( 'Columns', 'sportspress' ),
		'singular_name' => __( 'Column', 'sportspress' ),
		'add_new_item' => __( 'Add New Column', 'sportspress' ),
		'edit_item' => __( 'Edit Column', 'sportspress' ),
		'new_item' => __( 'New', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'search_items' => __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
		'not_found_in_trash' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Columns', 'sportspress' ),
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'has_archive' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sportspress_column_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_column', $args );
}
add_action( 'init', 'sportspress_column_post_init' );

function sportspress_column_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
		'sp_equation' => __( 'Equation', 'sportspress' ),
		'sp_precision' => __( 'Rounding', 'sportspress' ),
		'sp_order' => __( 'Sort Order', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_column_columns', 'sportspress_column_edit_columns' );

function sportspress_column_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sportspress_column_details_meta', 'sp_column', 'normal', 'high' );
}

function sportspress_column_details_meta( $post ) {
	$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
	$order = get_post_meta( $post->ID, 'sp_order', true );
	$priority = get_post_meta( $post->ID, 'sp_priority', true );
	$precision = get_post_meta( $post->ID, 'sp_precision', true );

	// Defaults
	if ( $precision == '' ) $precision = 0;
	?>
	<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
	</p>
	<p><strong><?php _e( 'Equation', 'sportspress' ); ?></strong></p>
	<p class="sp-equation-selector">
		<?php
		foreach ( $equation as $piece ):
			sportspress_equation_selector( $post->ID, $piece, array( 'team_event', 'result', 'outcome' ) );
		endforeach;
		?>
	</p>
	<p><strong><?php _e( 'Rounding', 'sportspress' ); ?></strong></p>
	<p class="sp-precision-selector">
		<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>" placeholder="0">
	</p>
	<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
	<p class="sp-order-selector">
		<select name="sp_priority">
			<?php
			$options = array( '0' => __( 'Disable', 'sportspress' ) );
			$count = wp_count_posts( 'sp_column' );
			for( $i = 1; $i <= $count->publish; $i++ ):
				$options[ $i ] = $i;
			endfor;
			foreach ( $options as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $priority, false ), $value );
			endforeach;
			?>
		</select>
		<select name="sp_order">
			<?php
			$options = array( 'DESC' => __( 'Descending', 'sportspress' ), 'ASC' => __( 'Ascending', 'sportspress' ) );
			foreach ( $options as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $order, false ), $value );
			endforeach;
			?>
		</select>
	</p>
	<?php
	sportspress_nonce();
}
