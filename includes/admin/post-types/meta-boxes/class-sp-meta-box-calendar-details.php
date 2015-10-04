<?php
/**
 * Calendar Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Details
 */
class SP_Meta_Box_Calendar_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$taxonomies = get_object_taxonomies( 'sp_calendar' );
		$caption = get_post_meta( $post->ID, 'sp_caption', true );
		$status = get_post_meta( $post->ID, 'sp_status', true );
		$date = get_post_meta( $post->ID, 'sp_date', true );
		$date_from = get_post_meta( $post->ID, 'sp_date_from', true );
		$date_to = get_post_meta( $post->ID, 'sp_date_to', true );
		$teams = get_post_meta( $post->ID, 'sp_team', false );
		$table_id = get_post_meta( $post->ID, 'sp_table', true );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		?>
		<div>
			<p><strong><?php _e( 'Heading', 'sportspress' ); ?></strong></p>
			<p><input type="text" id="sp_caption" name="sp_caption" value="<?php echo esc_attr( $caption ); ?>" placeholder="<?php echo esc_attr( get_the_title() ); ?>"></p>

			<p><strong><?php _e( 'Status', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'name' => 'sp_status',
					'id' => 'sp_status',
					'selected' => $status,
				);
				sp_dropdown_statuses( $args );
				?>
			</p>
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
				<p class="sp-date-range">
					<input type="text" class="sp-datepicker-from" name="sp_date_from" value="<?php echo $date_from ? $date_from : date_i18n( 'Y-m-d' ); ?>" size="10">
					:
					<input type="text" class="sp-datepicker-to" name="sp_date_to" value="<?php echo $date_to ? $date_to : date_i18n( 'Y-m-d' ); ?>" size="10">
				</p>
			</div>
			<?php
			foreach ( $taxonomies as $taxonomy ) {
				sp_taxonomy_field( $taxonomy, $post, true );
			}
			?>
			<p><strong><?php _e( 'Team', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$args = array(
					'post_type' => 'sp_team',
					'name' => 'sp_team[]',
					'selected' => $teams,
					'values' => 'ID',
					'class' => 'widefat',
					'property' => 'multiple',
					'chosen' => true,
					'placeholder' => __( 'All', 'sportspress' ),
				);
				if ( ! sp_dropdown_pages( $args ) ):
					sp_post_adder( 'sp_team', __( 'Add New', 'sportspress' )  );
				endif;
				?>
			</p>
			<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
			<p>
				<select name="sp_order">
					<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
					<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
				</select>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_caption', esc_attr( sp_array_value( $_POST, 'sp_caption', 0 ) ) );
		update_post_meta( $post_id, 'sp_status', sp_array_value( $_POST, 'sp_status', 0 ) );
		update_post_meta( $post_id, 'sp_date', sp_array_value( $_POST, 'sp_date', 0 ) );
		update_post_meta( $post_id, 'sp_date_from', sp_array_value( $_POST, 'sp_date_from', null ) );
		update_post_meta( $post_id, 'sp_date_to', sp_array_value( $_POST, 'sp_date_to', null ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', array() ) );
		sp_update_post_meta_recursive( $post_id, 'sp_team', sp_array_value( $_POST, 'sp_team', array() ) );
	}
}