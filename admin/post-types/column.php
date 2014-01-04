<?php
function sp_column_cpt_init() {
	$name = __( 'Table Columns', 'sportspress' );
	$singular_name = __( 'Table Column', 'sportspress' );
	$lowercase_name = __( 'table columns', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_column_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_column', $args );
}
add_action( 'init', 'sp_column_cpt_init' );

function sp_column_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_equation' => __( 'Equation', 'sportspress' ),
		'sp_order' => __( 'Sort Order', 'sportspress' ),
		'sp_abbreviation' => __( 'Abbreviation', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_column_columns', 'sp_column_edit_columns' );

function sp_column_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sp_column_details_meta', 'sp_column', 'normal', 'high' );
}

function sp_column_details_meta( $post ) {
	$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
	$order = get_post_meta( $post->ID, 'sp_order', true );
	$priority = get_post_meta( $post->ID, 'sp_priority', true );
	$precision = get_post_meta( $post->ID, 'sp_precision', true );
	$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );

	// Defaults
	if ( $precision == '' ) $precision = 1;
	?>
	<p><strong><?php _e( 'Equation', 'sportspress' ); ?></strong></p>
	<p class="sp-equation-selector">
		<?php
		foreach ( $equation as $piece ):
			sp_get_equation_selector( $post->ID, $piece, array( 'team_event', 'result', 'outcome' ) );
		endforeach;
		?>
	</p>
	<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
	<p class="sp-order-selector">
		<select name="sp_priority">
			<?php
			$options = array( '0' => __( 'Disable', 'sportspress' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10',  );
			foreach ( $options as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $priority, false ), $value );
			endforeach;
			?>
		</select>
		<select name="sp_order"<?php if ( ! $priority ): ?> disabled="disabled;"<?php endif; ?>>
			<?php
			$options = array( 'DESC' => __( 'Descending', 'sportspress' ), 'ASC' => __( 'Ascending', 'sportspress' ) );
			foreach ( $options as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $order, false ), $value );
			endforeach;
			?>
		</select>
	</p>
	<p><strong><?php _e( 'Precision', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>">
	</p>
	<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_abbreviation" type="text" size="4" id="sp_abbreviation" value="<?php echo $abbreviation; ?>">
	</p>
	<?php
	sp_nonce();
}
?>