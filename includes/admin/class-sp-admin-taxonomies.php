<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handles taxonomies in admin
 *
 * @class 		SP_Admin_Taxonomies
 * @version		0.8.5
 * @package		SportsPress/Admin
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Add form
		add_action( 'sp_venue_add_form_fields', array( $this, 'add_venue_fields' ) );
		add_action( 'sp_venue_edit_form_fields', array( $this, 'edit_venue_fields' ), 10, 1 );
		add_action( 'edited_sp_venue', array( $this, 'save_venue_fields' ), 10, 1 );
		add_action( 'create_sp_venue', array( $this, 'save_venue_fields' ), 10, 1 );

		// Add columns
		add_filter( 'manage_edit-sp_venue_columns', array( $this, 'venue_columns' ) );
		add_filter( 'manage_sp_venue_custom_column', array( $this, 'venue_column' ), 10, 3 );
	}

	/**
	 * Category thumbnail fields.
	 *
	 * @access public
	 * @return void
	 */
	public function add_venue_fields() {
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
			$latitude = sp_array_value( $term_meta, 'sp_latitude', '40.7324319' );
			$longitude = sp_array_value( $term_meta, 'sp_longitude', '-73.82480799999996' );
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
			<p><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
		</div>
	<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_venue_fields( $term ) {
	 	$t_id = $term->term_id;
		$term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_address]"><?php _e( 'Address', 'sportspress' ); ?></label></th>
			<td>
				<input type="text" class="sp-address" name="term_meta[sp_address]" id="term_meta[sp_address]" value="<?php echo esc_attr( $term_meta['sp_address'] ) ? esc_attr( $term_meta['sp_address'] ) : ''; ?>">
				<p><div class="sp-location-picker"></div><br>
				<span class="description"><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></span></p>
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

	/**
	 * save_category_fields function.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @return void
	 */
	public function save_venue_fields( $term_id ) {
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

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function venue_columns( $columns ) {
		$new_columns          = array();
		$new_columns['sp_address'] = __( 'Address', 'sportspress' );
		$new_columns['posts'] = __( 'Events', 'sportspress' );

		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function venue_column( $columns, $column, $id ) {

		if ( $column == 'sp_address' ) {

			$term_meta = get_option( "taxonomy_$id" );

			$address = ( isset( $term_meta['sp_address'] ) ? $term_meta['sp_address'] : '&mdash;' );

			$columns .= $address;

		}

		return $columns;
	}
}

new SP_Admin_Taxonomies();
