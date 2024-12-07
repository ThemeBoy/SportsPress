<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Handles taxonomies in admin
 *
 * @class       SP_Admin_Taxonomies
 * @version     2.7.23
 * @package     SportsPress/Admin
 * @category    Class
 * @author      ThemeBoy
 */
class SP_Admin_Taxonomies {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Add league field
		add_action( 'sp_league_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 1 );
		add_action( 'edited_sp_league', array( $this, 'save_fields' ), 10, 1 );

		// Add season field
		add_action( 'sp_season_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 1 );
		add_action( 'edited_sp_season', array( $this, 'save_fields' ), 10, 1 );

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

		// Add job field
		add_action( 'sp_role_edit_form_fields', array( $this, 'edit_taxonomy_fields' ), 10, 1 );
		add_action( 'edited_sp_role', array( $this, 'save_fields' ), 10, 1 );

		// Change league columns
		add_filter( 'manage_edit-sp_league_columns', array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_sp_league_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change season columns
		add_filter( 'manage_edit-sp_season_columns', array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_sp_season_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change venue columns
		add_filter( 'manage_edit-sp_venue_columns', array( $this, 'venue_columns' ) );
		add_filter( 'manage_sp_venue_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change position columns
		add_filter( 'manage_edit-sp_position_columns', array( $this, 'position_columns' ) );
		add_filter( 'manage_sp_position_custom_column', array( $this, 'column_value' ), 10, 3 );

		// Change job columns
		add_filter( 'manage_edit-sp_role_columns', array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_sp_role_custom_column', array( $this, 'column_value' ), 10, 3 );
	}

	/**
	 * Edit league/season fields.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_taxonomy_fields( $term ) {
		$t_id = $term->term_id;
		?>
		<?php if ( function_exists( 'get_term_meta' ) ) { ?>
			<?php $order = get_term_meta( $t_id, 'sp_order', true ); ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="sp_order"><?php esc_html_e( 'Order', 'sportspress' ); ?></label></th>
				<td><input name="sp_order" class="sp-number-input" type="text" step="1" size="4" id="sp_order" value="<?php echo (int) $order; ?>"></td>
			</tr>
		<?php } ?>
		<?php
	}

	/**
	 * Add venue fields.
	 *
	 * @access public
	 * @return void
	 */
	public function add_venue_fields() {
		$args = array(
			'orderby'    => 'id',
			'order'      => 'DESC',
			'hide_empty' => false,
			'number'     => 1,
		);
		
		// Get latitude and longitude from the last added venue
		$terms = get_terms( 'sp_venue', $args );
		if ( $terms && array_key_exists( 0, $terms ) && is_object( reset( $terms ) ) ) {
			$term      = reset( $terms );
			$t_id      = $term->term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$latitude  = sp_array_value( $term_meta, 'sp_latitude', '-37.8165647' );
			$longitude = sp_array_value( $term_meta, 'sp_longitude', '144.9475055' );
			$address   = sp_array_value( $term_meta, 'sp_address', '' );
		}else{
			// Sanitize latitude and longitude, fallback to default.
			$latitude  = '-37.8165647';
			$longitude = '144.9475055';
			$address   = '';
		}
		?>
		<div class="form-field">
			<div id="sp-location-picker" class="sp-location-picker" style="width: 95%; height: 320px"></div>
			<p><?php esc_html_e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
		</div>
		<div class="form-field">
			<label for="term_meta[sp_address]"><?php esc_html_e( 'Address', 'sportspress' ); ?></label>
			<input type="text" class="sp-address" name="term_meta[sp_address]" id="term_meta[sp_address]" value="<?php echo esc_attr( $address ); ?>">
		</div>
		<div class="form-field">
			<label for="term_meta[sp_latitude]"><?php esc_html_e( 'Latitude', 'sportspress' ); ?></label>
			<input type="text" class="sp-latitude" name="term_meta[sp_latitude]" id="term_meta[sp_latitude]" value="<?php echo esc_attr( $latitude ); ?>">
		</div>
		<div class="form-field">
			<label for="term_meta[sp_longitude]"><?php esc_html_e( 'Longitude', 'sportspress' ); ?></label>
			<input type="text" class="sp-longitude" name="term_meta[sp_longitude]" id="term_meta[sp_longitude]" value="<?php echo esc_attr( $longitude ); ?>">
		</div>
		<?php
		do_action( 'sp_admin_geocoder_scripts' );
	}

	/**
	 * Edit venue fields.
	 *
	 * @access public
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_venue_fields( $term ) {
		$t_id      = $term->term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$latitude  = is_numeric( $term_meta['sp_latitude'] ) ? $term_meta['sp_latitude'] : '';
		$longitude = is_numeric( $term_meta['sp_longitude'] ) ? $term_meta['sp_longitude'] : '';
		$address   = $term_meta['sp_address'] ? $term_meta['sp_address'] : '';
		?>
		<tr class="form-field">
			<td colspan="2">
				<p><div id="sp-location-picker" class="sp-location-picker" style="width: 95%; height: 320px"></div></p>
				<p class="description"><?php esc_html_e( "Drag the marker to the venue's location.", 'sportspress' ); ?></p>
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_address]"><?php esc_html_e( 'Address', 'sportspress' ); ?></label></th>
			<td>
				<input type="text" class="sp-address" name="term_meta[sp_address]" id="term_meta[sp_address]" value="<?php echo esc_attr( $address ); ?>">
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_latitude]"><?php esc_html_e( 'Latitude', 'sportspress' ); ?></label></th>
			<td>
				<input type="text" class="sp-latitude" name="term_meta[sp_latitude]" id="term_meta[sp_latitude]" value="<?php echo esc_attr( $latitude ); ?>">
			</td>
		</tr>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_longitude]"><?php esc_html_e( 'Longitude', 'sportspress' ); ?></label></th>
			<td>
				<input type="text" class="sp-longitude" name="term_meta[sp_longitude]" id="term_meta[sp_longitude]" value="<?php echo esc_attr( $longitude ); ?>">
			</td>
		</tr>
		<?php
		do_action( 'sp_admin_geocoder_scripts' );
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
			<label><?php esc_html_e( 'Statistics', 'sportspress' ); ?></label>
			<select name="term_meta[sp_sections][]" id="term_meta[sp_sections][]" class="widefat chosen-select
			<?php
			if ( is_rtl() ) :
				?>
				 chosen-rtl<?php endif; ?>" multiple="multiple">
				<?php
				$options = apply_filters(
					'sportspress_performance_sections',
					array(
						0 => esc_attr__( 'Offense', 'sportspress' ),
						1 => esc_attr__(
							'Defense',
							'sportspress'
						),
					)
				);
				foreach ( $options as $key => $value ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( true ), esc_html( $value ) );
				endforeach;
				?>
			</select>
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
		$t_id     = $term->term_id;
		$sections = sp_get_term_sections( $t_id );
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="term_meta[sp_sections]"><?php esc_html_e( 'Statistics', 'sportspress' ); ?></label></th>
			<input type="hidden" name="term_meta[sp_sections]" value="">
			<td>
				<select name="term_meta[sp_sections][]" id="term_meta[sp_sections][]" class="widefat chosen-select
				<?php
				if ( is_rtl() ) :
					?>
					 chosen-rtl<?php endif; ?>" multiple="multiple">
					<?php
					$options = apply_filters(
						'sportspress_performance_sections',
						array(
							0 => esc_attr__( 'Offense', 'sportspress' ),
							1 => esc_attr__( 'Defense', 'sportspress' ),
						)
					);
					foreach ( $options as $key => $value ) :
						printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( in_array( $key, $sections ), true, false ), esc_html( $value ) );
					endforeach;
					?>
				</select>
			</td>
		</tr>
		<?php if ( function_exists( 'get_term_meta' ) ) { ?>
			<?php $order = get_term_meta( $t_id, 'sp_order', true ); ?>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="sp_order"><?php esc_html_e( 'Order', 'sportspress' ); ?></label></th>
				<td><input name="sp_order" class="sp-number-input" type="text" step="1" size="4" id="sp_order" value="<?php echo (int) $order; ?>"></td>
			</tr>
		<?php } ?>
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
			$t_id      = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys  = array_keys( wp_unslash( $_POST['term_meta'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			foreach ( $cat_keys as $key ) {
				if ( isset( $_POST['term_meta'][ $key ] ) ) {
					if ( is_array( $_POST['term_meta'][ $key ] ) ) {
						// If it's an array, loop through each element and sanitize
						$term_meta[ $key ] = array_map( 'sanitize_text_field', wp_unslash( $_POST['term_meta'][ $key ] ) );
					} else {
						// If it's not an array, just sanitize the single value
						$term_meta[ $key ] = sanitize_text_field( wp_unslash( $_POST['term_meta'][ $key ] ) );
					}
				}
			}
			update_option( "taxonomy_$t_id", $term_meta );
		}
		if ( function_exists( 'add_term_meta' ) ) {
			update_term_meta( $term_id, 'sp_order', (int) sp_array_value( $_POST, 'sp_order', 0, 'int' ) );
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
		$new_columns = array();

		if ( function_exists( 'get_term_meta' ) ) {
			$new_columns['sp_order'] = esc_attr__( 'Order', 'sportspress' );
		}

		if ( array_key_exists( 'posts', $columns ) ) {
			$new_columns['posts'] = $columns['posts'];

			unset( $columns['posts'] );
		}

		return array_merge( $columns, $new_columns );
	}

	/**
	 * Change venue columns in admin.
	 *
	 * @access public
	 * @param mixed $columns
	 * @return array
	 */
	public function venue_columns( $columns ) {
		$new_columns               = array();
		$new_columns['sp_address'] = esc_attr__( 'Address', 'sportspress' );

		if ( array_key_exists( 'posts', $columns ) ) {
			$new_columns['posts'] = $columns['posts'];
			unset( $columns['posts'] );
		}

		unset( $columns['description'] );
		unset( $columns['slug'] );

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
		$new_columns                = array();
		$new_columns['sp_sections'] = esc_attr__( 'Statistics', 'sportspress' );

		if ( function_exists( 'get_term_meta' ) ) {
			$new_columns['sp_order'] = esc_attr__( 'Order', 'sportspress' );
		}

		if ( array_key_exists( 'posts', $columns ) ) {
			$new_columns['posts'] = $columns['posts'];
			unset( $columns['posts'] );
		}

		unset( $columns['description'] );
		unset( $columns['slug'] );

		return array_merge( $columns, $new_columns );
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

			$address = ( isset( $term_meta['sp_address'] ) ? esc_attr($term_meta['sp_address']) : '&mdash;' );

			$columns .= $address;

		} elseif ( $column == 'sp_sections' ) {

			$options = apply_filters(
				'sportspress_performance_sections',
				array(
					0 => esc_attr__( 'Offense', 'sportspress' ),
					1 => esc_attr__(
						'Defense',
						'sportspress'
					),
				)
			);

			$sections = sp_get_term_sections( $id );

			$section_names = array();

			if ( is_array( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( array_key_exists( $section, $options ) ) {
						$section_names[] = $options[ $section ];
					}
				}
			}

			$columns .= implode( ', ', $section_names );

		} elseif ( $column == 'sp_order' ) {

			$columns = (int) get_term_meta( $id, 'sp_order', true );

		}

		return $columns;
	}
}

new SP_Admin_Taxonomies();
