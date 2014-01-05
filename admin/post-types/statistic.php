<?php
function sp_statistic_cpt_init() {
	$name = __( 'Statistics', 'sportspress' );
	$singular_name = __( 'Statistic', 'sportspress' );
	$lowercase_name = __( 'statistics', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_statistic_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_statistic', $args );
}
add_action( 'init', 'sp_statistic_cpt_init' );

function sp_statistic_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
		'sp_format' => __( 'Format', 'sportspress' ),
		'sp_precision' => __( 'Precision', 'sportspress' ),
		'sp_equation' => __( 'Equation', 'sportspress' ),
		'sp_order' => __( 'Sort Order', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_statistic_columns', 'sp_statistic_edit_columns' );

function sp_statistic_meta_init() {
	add_meta_box( 'sp_equationdiv', __( 'Details', 'sportspress' ), 'sp_statistic_equation_meta', 'sp_statistic', 'normal', 'high' );
}

function sp_statistic_equation_meta( $post ) {
	global $sportspress_config_formats;

	$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
	$order = get_post_meta( $post->ID, 'sp_order', true );
	$priority = get_post_meta( $post->ID, 'sp_priority', true );
	$precision = get_post_meta( $post->ID, 'sp_precision', true );
	
	// Defaults
	if ( $precision == '' ) $precision = 1;
	?>
	<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
	</p>
	<p><strong><?php _e( 'Format', 'sportspress' ); ?></strong></p>
	<p class="sp-format-selector">
		<select name="sp_format">
			<?php
			foreach ( $sportspress_config_formats as $key => $value ):
				printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $priority, false ), $value );
			endforeach;
			?>
		</select>
	</p>
	<p><strong><?php _e( 'Precision', 'sportspress' ); ?></strong></p>
	<p class="sp-precision-selector">
		<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>" placeholder="1">
	</p>
	<p><strong><?php _e( 'Equation', 'sportspress' ); ?></strong></p>
	<p class="sp-equation-selector">
		<?php
		foreach ( $equation as $piece ):
			sp_get_equation_selector( $post->ID, $piece, array( 'player_event' ) );
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
	<?php
	sp_nonce();
}
?>