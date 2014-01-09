<?php
function sportspress_venue_term_init() {
	$name = __( 'Venues', 'sportspress' );
	$singular_name = __( 'Venue', 'sportspress' );
	$lowercase_name = __( 'venue', 'sportspress' );
	$object_type = array( 'sp_event', 'sp_calendar' );
	$labels = sportspress_get_term_labels( $name, $singular_name, $lowercase_name );
	$args = array(
		'label' => $name,
		'labels' => $labels,
		'public' => true,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => 'venue' )
	);
	register_taxonomy( 'sp_venue', $object_type, $args );
	register_taxonomy_for_object_type( 'sp_venue', 'sp_event' );
	register_taxonomy_for_object_type( 'sp_venue', 'sp_calendar' );
}
add_action( 'init', 'sportspress_venue_term_init' );

function sportspress_venue_edit_form_fields( $term ) {
 	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[sp_address]"><?php _e( 'Address', 'sportspress' ); ?></label></th>
		<td>
			<input type="text" name="term_meta[sp_address]" id="term_meta[sp_address]" value="<?php echo esc_attr( $term_meta['sp_address'] ) ? esc_attr( $term_meta['sp_address'] ) : ''; ?>">
		</td>
	</tr>
<?php
}
add_action( 'sp_venue_edit_form_fields', 'sportspress_venue_edit_form_fields', 10, 2 );

	function sportspress_venue_add_form_fields() {
	?>
	<div class="form-field">
		<label for="term_meta[sp_address]"><?php _e( 'Address', 'sportspress' ); ?></label>
		<input type="text" name="term_meta[sp_address]" id="term_meta[sp_address]" value="">
	</div>
<?php
}
add_action( 'sp_venue_add_form_fields', 'sportspress_venue_add_form_fields', 10, 2 );

function sportspress_save_venue_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][ $key ] ) ) {
				$term_meta[$key] = $_POST['term_meta'][ $key ];
			}
		}
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_sp_venue', 'sportspress_save_venue_meta', 10, 2 );
add_action( 'create_sp_venue', 'sportspress_save_venue_meta', 10, 2 );
