<?php
/**
 * Table Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version   2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Table_Details
 */
class SP_Meta_Box_Table_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$taxonomies = get_object_taxonomies( 'sp_table' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$select = get_post_meta( $post->ID, 'sp_select', true );
		$post_type = sp_get_post_mode_type( $post->ID );
		$date = get_post_meta( $post->ID, 'sp_date', true );
		$date_from = get_post_meta( $post->ID, 'sp_date_from', true );
		$date_to = get_post_meta( $post->ID, 'sp_date_to', true );
		$date_past = get_post_meta( $post->ID, 'sp_date_past', true );
		$date_relative = get_post_meta( $post->ID, 'sp_date_relative', true );
		$event_status = get_post_meta( $post->ID, 'sp_event_status', true );
		if ( empty( $event_status ) ) {
			$event_status = array( 'publish', 'future' );
		}
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<div class="sp-date-selector">
				<p><strong><?php _e( 'Date', 'sportspress' ); ?></strong></p>
				<p>
					<?php
					$args = array(
						'name' => 'sp_date',
						'id' => 'sp_date',
						'selected' => $date,
					);
					sp_dropdown_dates( $args );
					?>
				</p>
				<div class="sp-date-range">
					<p class="sp-date-range-absolute">
						<input type="text" class="sp-datepicker-from" name="sp_date_from" value="<?php echo $date_from ? $date_from : date_i18n( 'Y-m-d' ); ?>" size="10">
						:
						<input type="text" class="sp-datepicker-to" name="sp_date_to" value="<?php echo $date_to ? $date_to : date_i18n( 'Y-m-d' ); ?>" size="10">
					</p>

					<p class="sp-date-range-relative">
						<?php _e( 'Past', 'sportspress' ); ?>
						<input type="number" min="0" step="1" class="tiny-text" name="sp_date_past" value="<?php echo '' !== $date_past ? $date_past : 7; ?>">
						<?php _e( 'days', 'sportspress' ); ?>
					</p>

					<p class="sp-date-relative">
						<label>
							<input type="checkbox" name="sp_date_relative" value="1" id="sp_date_relative" <?php checked( $date_relative ); ?>>
							<?php _e( 'Relative', 'sportspress' ); ?>
						</label>
					</p>
				</div>
			</div>

			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			do_action( 'sportspress_meta_box_table_details', $post->ID );
			?>
			<p><strong>
				<?php echo sp_get_post_mode_label( $post->ID ); ?>
			</strong></p>
			<p class="sp-select-setting">
				<select name="sp_select">
					<option value="auto" <?php selected( 'auto', $select ); ?>><?php _e( 'Auto', 'sportspress' ); ?></option>
					<option value="manual" <?php selected( 'manual', $select ); ?>><?php _e( 'Manual', 'sportspress' ); ?></option>
				</select>
			</p>
			<?php
			if ( 'manual' == $select ) {
				sp_post_checklist( $post->ID, $post_type, ( 'auto' == $select ? 'none' : 'block' ), array( 'sp_league', 'sp_season' ), null, 'sp_team' );
				sp_post_adder( $post_type, __( 'Add New', 'sportspress' ) );
			}
			?>
			<p><strong><?php _e( 'Event Status (with results)', 'sportspress' ); ?></strong></p>
			<p>
				<input type="checkbox" name="sp_event_status[]" value="publish" <?php echo ( in_array( "publish" , $event_status) ) ? 'checked' : false; ?>> Published/Played<br>
				<input type="checkbox" name="sp_event_status[]" value="future" <?php echo ( in_array( "future" , $event_status) ) ? 'checked' : false; ?>> Scheduled/Future<br>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
		update_post_meta( $post_id, 'sp_date', sp_array_value( $_POST, 'sp_date', 0 ) );
		update_post_meta( $post_id, 'sp_date_from', sp_array_value( $_POST, 'sp_date_from', null ) );
		update_post_meta( $post_id, 'sp_date_to', sp_array_value( $_POST, 'sp_date_to', null ) );
		update_post_meta( $post_id, 'sp_date_past', sp_array_value( $_POST, 'sp_date_past', 0 ) );
		update_post_meta( $post_id, 'sp_date_relative', sp_array_value( $_POST, 'sp_date_relative', 0 ) );
		$tax_input = sp_array_value( $_POST, 'tax_input', array() );
		update_post_meta( $post_id, 'sp_main_league', in_array( 'auto', sp_array_value( $tax_input, 'sp_league' ) ) );
		update_post_meta( $post_id, 'sp_current_season', in_array( 'auto', sp_array_value( $tax_input, 'sp_season' ) ) );
		update_post_meta( $post_id, 'sp_select', sp_array_value( $_POST, 'sp_select', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
		update_post_meta( $post_id, 'sp_event_status', sp_array_value( $_POST, 'sp_event_status', array() ) );
	}
}