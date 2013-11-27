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
		'show_in_nav_menus' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_outcome_meta_init',
		'show_in_menu' => 'edit.php?post_type=sp_event'
	);
	register_post_type( 'sp_outcome', $args );
}
add_action( 'init', 'sp_outcome_cpt_init' );

function sp_outcome_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_equation' => __( 'Equation', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_outcome_columns', 'sp_outcome_edit_columns' );

function sp_outcome_meta_init() {
	add_meta_box( 'sp_equationdiv', __( 'Equation', 'sportspress' ), 'sp_outcome_equation_meta', 'sp_outcome', 'normal', 'high' );
}

function sp_outcome_equation_meta( $post ) {
	$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
	?>
	<div>
		<p class="sp-equation-selector">
			<?php
			foreach ( $equation as $piece ):
				sp_get_equation_selector( $post->ID, $piece, array( 'result' ) );
			endforeach;
			?>
		</p>
	</div>
	<?php
	sp_nonce();
}
?>