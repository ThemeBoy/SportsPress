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
		'show_in_nav_menus' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'show_in_menu' => 'edit.php?post_type=sp_event',
		'capability_type' => 'sp_config'
	);
	register_post_type( 'sp_result', $args );
}
add_action( 'init', 'sp_result_cpt_init' );

function sp_result_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' )
	);
	return $columns;
}
add_filter( 'manage_edit-sp_result_columns', 'sp_result_edit_columns' );
?>