<?php
function sp_sport_term_init() {
	$name = __( 'Sports', 'sportspress' );
	$singular_name = __( 'Sport', 'sportspress' );
	$lowercase_name = __( 'sport', 'sportspress' );
	$object_type = array( 'sp_stat', 'sp_metric' );
	$labels = sp_tax_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'sport' )
	);
	register_taxonomy( 'sp_sport', $object_type, $args );
}
add_action( 'init', 'sp_sport_term_init' );
?>