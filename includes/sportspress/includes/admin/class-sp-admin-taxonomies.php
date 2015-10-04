<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handles taxonomies in admin
 *
 * @class 		SP_Admin_Taxonomies
 * @version		1.9.7
 * @package		SportsPress/Admin
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Add venue field
		add_action( 'sp_venue_add_form_fields', array( $this, 'add_venue_fields' ) );
		add_action( 'sp_venue_edit_form_fields', array( $this, 'edit_venue_fields' ), 10, 1 );
		add_action( 'edited_sp_venue', array( $this, 'save_fields' ), 10, 1 );
		add_action( 'create_sp_venue', array( $this, 'save_fields' ), 10, 1 );

		// Add position field
		add_action( 'sp_position_add_form_fields', array( $this, 'add_position_fields' ) );
		add_action( 'sp_position_edit_form_fields', array( $this, 'edit_position_fields' ), 10, 1 );
		add_action( 'edited_sp_position', array( $this, 'save_fields' ), 10, 1 );
		add_action( 'create_sp_position', array( $this, 'save_fields' ), 10, 1 );

		// Change league and season columns
		add_filter( 'manage_edit-sp_league_columns', array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_edit-sp_season_columns', array( $this, 'taxonomy_columns' ) );

		// Change venue columns
		add_filter( 'manage_edit-sp_venue_columns', array( $this, 'venue_columns' ) );
		add_filter( 'manage_sp_venue_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change position columns
		add_filter( 'manage_edit-sp_position_columns', array( $this, 'position_columns' ) );
		add_filter( 'manage_sp_position_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change role columns
		add_filter( 'manage_edit-sp_role_columns', array( $this, 'role_columns' ) );
	}

	/**
	 * Add venue fields.
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
			<p><div class="sp-location-picker"></div></p>
			<p><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
		</div>
		<div class="form-field">
			<label for="term_meta[sp_latitude]"><?php _e( 'Latitude', 'sportspress' ); ?></label>
			<input type="text" class="sp-latitude" name="term_meta[sp_latitude]" id="term_meta[sp_latitude]" value="<?php echo esc_attr( $latitude ); ?>">
		</div>
		<div class="form-field">
			<label for="term_meta[sp_longitude]"><?php _e( 'Longitude', 'sportspress' ); ?></label>
			<input type="text" class="sp-longitude" name="term_meta[sp_longitude]" id="term_meta[sp_longitude]" value="<?php echo esc_attr( $longitude ); ?>">
		</div>
	<?php
	}

	/**
	 * Edit venue fields.
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
				<p><div class="sp-location-picker"></div></p>
				<p class="description"><?php _e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
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
	 * Add position fields.
	 *
	 * @access public
	 * @return void
	 */
	public function add_position_fields() {
		?>
		<div class="form-field">
			<label for="term_meta[sp_caption]"><?php _e( 'Heading', 'sportspress' ); ?></label>
			<input type="text" name="term_meta[sp_caption]" id="term_meta[sp_caption]" value="">
			<p class="description"><?php _e( 'Used for events.', 'sportspress' ); ?></p>
		</div>
	<?php
	}

	/**
	 * Edit position fields.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_position_fields( $term ) {
	 	$t_id = $term->term_id;
		$term_meta = get_option( "taxonomy_$t_id" ); ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_caption]"><?php _e( 'Heading', 'sportspress' ); ?></label></th>
			<td>
				<input type="text" name="term_meta[sp_caption]" id="term_meta[sp_caption]" value="<?php echo esc_attr( $term_meta['sp_caption'] ) ? esc_attr( $term_meta['sp_caption'] ) : ''; ?>">
				<p class="description"><?php _e( 'Used for events.', 'sportspress' ); ?></p>
			</td>
		</tr>
	<?php
	}

	/**
	 * Save fields.
	 *
	 * @access public
	 * @param mixed $term_id Term ID being saved
	 * @return void
	 */
	public function save_fields( $term_id ) {
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
	 * Posts column changed to Events in admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function taxonomy_columns( $columns ) {
		$columns['posts'] = __( 'Events', 'sportspress' );
		return $columns;
	}

	/**
	 * Change venue columns in admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function venue_columns( $columns ) {
		$new_columns = array();
		$new_columns['sp_address'] = __( 'Address', 'sportspress' );
		$new_columns['posts'] = __( 'Events', 'sportspress' );

		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Posts column changed to Players in admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function position_columns( $columns ) {
		$new_columns = array();
		$new_columns['sp_caption'] = __( 'Heading', 'sportspress' );
		$new_columns['posts'] = __( 'Players', 'sportspress' );

		unset( $columns['description'] );
		unset( $columns['slug'] );
		unset( $columns['posts'] );

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Posts column changed to Staff in admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function role_columns( $columns ) {
		$columns['posts'] = __( 'Staff', 'sportspress' );
		return $columns;
	}

	/**
	 * Column value added to category admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @param mixed $column
	 * @param mixed $id
	 * @return array
	 */
	public function column_value( $columns, $column, $id ) {

		if ( $column == 'sp_address' ) {

			$term_meta = get_option( "taxonomy_$id" );

			$address = ( isset( $term_meta['sp_address'] ) ? $term_meta['sp_address'] : '&mdash;' );

			$columns .= $address;

		} elseif ( $column == 'sp_caption' ) {

			$term_meta = get_option( "taxonomy_$id" );

			$caption = ( isset( $term_meta['sp_caption'] ) ? $term_meta['sp_caption'] : '&mdash;' );

			$columns .= $caption;

		}

		return $columns;
	}
}

new SP_Admin_Taxonomies();
