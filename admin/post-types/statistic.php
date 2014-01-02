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
		'sp_equation' => __( 'Equation', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' ),
		'sp_abbreviation' => __( 'Abbreviation', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_statistic_columns', 'sp_statistic_edit_columns' );

function sp_statistic_meta_init() {
	add_meta_box( 'sp_equationdiv', __( 'Equation', 'sportspress' ), 'sp_statistic_equation_meta', 'sp_statistic', 'normal', 'high' );
}

function sp_statistic_equation_meta( $post ) {
	$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
	$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
	?>
	<p class="sp-equation-selector">
		<?php
		foreach ( $equation as $piece ):
			sp_get_equation_selector( $post->ID, $piece, array( 'player_event' ) );
		endforeach;
		?>
	</p>
	<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_abbreviation" type="text" size="4" id="sp_abbreviation" value="<?php echo $abbreviation; ?>">
	</p>
	<?php
	sp_nonce();
}
?>