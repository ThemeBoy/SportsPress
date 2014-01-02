<?php
function sp_outcome_cpt_init() {
	$name = __( 'Outcomes', 'sportspress' );
	$singular_name = __( 'Outcome', 'sportspress' );
	$lowercase_name = __( 'outcome', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_outcome_meta_init',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_outcome', $args );
}
add_action( 'init', 'sp_outcome_cpt_init' );

function sp_outcome_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_abbreviation' => __( 'Abbreviation', 'sportspress' ),
		'sp_key' => __( 'Key', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_outcome_columns', 'sp_outcome_edit_columns' );

function sp_outcome_meta_init() {
	add_meta_box( 'sp_detailsdiv', __( 'Details', 'sportspress' ), 'sp_outcome_details_meta', 'sp_outcome', 'normal', 'high' );
}

function sp_outcome_details_meta( $post ) {
	$abbreviation = get_post_meta( $post->ID, 'sp_abbreviation', true );
	?>
	<p><strong><?php _e( 'Abbreviation', 'sportspress' ); ?></strong></p>
	<p>
		<input name="sp_abbreviation" type="text" size="4" id="sp_abbreviation" value="<?php echo $abbreviation; ?>" placeholder="<?php echo get_the_title( $post->ID ); ?>">
	</p>
	<?php
	sp_nonce();
}
?>