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
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_outcome', $args );
}
add_action( 'init', 'sp_outcome_cpt_init' );

function sp_outcome_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_outcome_columns', 'sp_outcome_edit_columns' );
?>