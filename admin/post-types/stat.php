<?php
function sp_stat_cpt_init() {
	$name = __( 'Statistics', 'sportspress' );
	$singular_name = __( 'Statistic', 'sportspress' );
	$lowercase_name = __( 'statistics', 'sportspress' );
	$labels = sp_cpt_labels( $name, $singular_name, $lowercase_name, true );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'hierarchical' => false,
		'supports' => array( 'title', 'page-attributes' ),
		'register_meta_box_cb' => 'sp_stat_meta_init',
		'rewrite' => array( 'slug' => 'stat' ),
		'show_in_menu' => 'edit.php?post_type=sp_team'
	);
	register_post_type( 'sp_stat', $args );
}
add_action( 'init', 'sp_stat_cpt_init' );

function sp_stat_edit_columns() {
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Label', 'sportspress' ),
		'sp_sport' => __( 'Sport', 'sportspress' ),
		'sp_equation' => __( 'Equation', 'sportspress' ),
	);
	return $columns;
}
add_filter( 'manage_edit-sp_stat_columns', 'sp_stat_edit_columns' );

function sp_stat_meta_init() {
	add_meta_box( 'sp_equationdiv', __( 'Equation', 'sportspress' ), 'sp_stat_equation_meta', 'sp_stat', 'normal', 'high' );
}

function sp_stat_equation_meta( $post ) {
	$args = array(
		'post_type' => 'sp_stat',
		'numberposts' => -1,
		'posts_per_page' => -1,
		'exclude' => $post->ID
	);
	$sports = get_the_terms( $post->ID, 'sp_sport' );
	if ( ! empty( $sports ) ):
		$terms = array();
		foreach ( $sports as $sport ):
			$terms[] = $sport->slug;
		endforeach;
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'sp_sport',
				'field' => 'slug',
				'terms' => $terms
			)
		);
	endif;
	$stats = get_posts( $args );
	?>
	<div>
		<p class="sp-equation-selector">
			<select data-remove-text="<?php _e( 'Remove', 'sportspress' ); ?>">
				<option value=""><?php _e( 'Select', 'sportspress' ); ?></option>
				<optgroup label="<?php _e( 'Events', 'sportspress' ); ?>">
					<option value="wins"><?php _e( 'Wins', 'sportspress' ); ?></option>
					<option value="draws"><?php _e( 'Draws', 'sportspress' ); ?></option>
					<option value="ties"><?php _e( 'Ties', 'sportspress' ); ?></option>
					<option value="losses"><?php _e( 'Losses', 'sportspress' ); ?></option>
				</optgroup>
				<optgroup label="<?php _e( 'Statistics', 'sportspress' ); ?>">
				<?php foreach ( $stats as $stat ): ?>
					<option value="<?php echo $stat->post_name; ?>"><?php echo $stat->post_title; ?></option>
				<?php endforeach; ?>
				</optgroup>
				<optgroup label="<?php _e( 'Operators', 'sportspress' ); ?>">
					<option value="+">&plus;</option>
					<option value="-">&minus;</option>
					<option value="X">&times;</option>
					<option value="/">&divide;</option>
					<option value="(">(</option>
					<option value=")">)</option>
				</optgroup>
				<optgroup label="<?php _e( 'Constants', 'sportspress' ); ?>">
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
				</optgroup>
			</select>
		</p>
	</div>
	<?php
}
?>