<?php
function sportspress_venue_term_init() {
	$labels = array(
		'name' => __( 'Venues', 'sportspress' ),
		'singular_name' => __( 'Venue', 'sportspress' ),
		'all_items' => __( 'All', 'sportspress' ),
		'edit_item' => __( 'Edit Venue', 'sportspress' ),
		'view_item' => __( 'View', 'sportspress' ),
		'update_item' => __( 'Update', 'sportspress' ),
		'add_new_item' => __( 'Add New', 'sportspress' ),
		'new_item_name' => __( 'Name', 'sportspress' ),
		'parent_item' => __( 'Parent', 'sportspress' ),
		'parent_item_colon' => __( 'Parent:', 'sportspress' ),
		'search_items' =>  __( 'Search', 'sportspress' ),
		'not_found' => __( 'No results found.', 'sportspress' ),
	);
	$args = array(
		'label' => __( 'Venues', 'sportspress' ),
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => false,
		'show_tagcloud' => false,
		'hierarchical' => true,
		'rewrite' => array( 'slug' => get_option( 'sportspress_venue_slug', 'venue' ) ),
	);
	$object_types = array( 'sp_event', 'sp_calendar', 'attachment' );
	register_taxonomy( 'sp_venue', $object_types, $args );
	foreach ( $object_types as $object_type ):
		register_taxonomy_for_object_type( 'sp_league', $object_type );
	endforeach;
}
add_action( 'init', 'sportspress_venue_term_init' );

function sportspress_venue_edit_form_fields( $term ) {
 	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[sp_address]"><?php _e( 'Address', 'sportspress' ); ?></label></th>
		<td>
			<input type="text" class="sp-address" name="term_meta[sp_address]" id="term_meta[sp_address]" value="<?php echo esc_attr( $term_meta['sp_address'] ) ? esc_attr( $term_meta['sp_address'] ) : ''; ?>">
			<p><div class="sp-location-picker"></div></p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[sp_latitude]"><?php _e( 'Latitude', 'sportspress' ); ?></label></th>
		<td>
			<input type="text" class="sp-latitude" name="term_meta[sp_latitude]" id="term_meta[sp_latitude]" value="<?php echo esc_attr( $term_meta['sp_latitude'] ) ? esc_attr( $term_meta['sp_latitude'] ) : ''; ?>">
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[sp_longitude]"><?php _e( 'Longitude', 'sportspress' ); ?></label></th>
		<td>
			<input type="text" class="sp-longitude" name="term_meta[sp_longitude]" id="term_meta[sp_longitude]" value="<?php echo esc_attr( $term_meta['sp_longitude'] ) ? esc_attr( $term_meta['sp_longitude'] ) : ''; ?>">
		</td>
	</tr>
<?php
}
add_action( 'sp_venue_edit_form_fields', 'sportspress_venue_edit_form_fields', 10, 2 );

	function sportspress_venue_add_form_fields() {

	$args = array(
		'orderby' => 'id',
		'order' => 'DESC',
		'hide_empty' => false,
		'number' => 1,
	);

	// Get latitude and longitude from the last added venue
	$terms = get_terms( 'sp_venue', $args );
	if ( $terms && array_key_exists( 0, $terms) && is_object( reset( $terms ) ) ):
		$term = reset( $terms );
 		$t_id = $term->term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$latitude = sportspress_array_value( $term_meta, 'sp_latitude', '40.7324319' );
		$longitude = sportspress_array_value( $term_meta, 'sp_longitude', '-73.82480799999996' );
	else:
		$latitude = '40.7324319';
		$longitude = '-73.82480799999996';
	endif;

	?>

	<div class="form-field">
		<label for="term_meta[sp_address]"><?php _e( 'Address', 'sportspress' ); ?></label>
		<input type="text" class="sp-address" name="term_meta[sp_address]" id="term_meta[sp_address]" value="">
		<input type="hidden" class="sp-latitude" name="term_meta[sp_latitude]" id="term_meta[sp_latitude]" value="<?php echo $latitude; ?>">
		<input type="hidden" class="sp-longitude" name="term_meta[sp_longitude]" id="term_meta[sp_longitude]" value="<?php echo $longitude; ?>">
		<p><div class="sp-location-picker"></div></p>
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
