<?php
/**
 * Calendar Columns
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.6.12
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Columns
 */
class SP_Meta_Box_Calendar_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$selected = (array) get_post_meta( $post->ID, 'sp_columns', true );
		$title_format = get_option( 'sportspress_event_list_title_format', 'title' );
		$time_format = get_option( 'sportspress_event_list_time_format', 'combined' );

		if ( is_array( $selected ) ) {
			$selected = array_filter( $selected );
		}

		$columns = array();

		if ( 'teams' === $title_format ) {
			$columns[ 'event' ] = __( 'Home', 'sportspress' ) . ' | ' . __( 'Away', 'sportspress' );
		} elseif ( 'homeaway' === $title_format ) {
			$columns[ 'event' ] = __( 'Teams', 'sportspress' );
		} else {
			$columns[ 'event' ] = __( 'Title', 'sportspress' );
		}

		if ( 'time' === $time_format || 'separate' === $time_format ) {
			$columns['time'] = __( 'Time', 'sportspress' );
		} elseif ( 'combined' === $time_format ) {
			$columns['time'] = __( 'Time/Results', 'sportspress' );
		}

		if ( 'results' === $time_format || 'separate' === $time_format ) {
			$columns['results'] = __( 'Results', 'sportspress' );
		}

		$columns['league'] = __( 'League', 'sportspress' );
		$columns['season'] = __( 'Season', 'sportspress' );
		$columns['venue'] = __( 'Venue', 'sportspress' );
		$columns['article'] = __( 'Article', 'sportspress' );
		$columns['day'] = __( 'Match Day', 'sportspress' );

		$columns = apply_filters( 'sportspress_calendar_columns', $columns );
		?>
		<div class="sp-instance">
			<ul class="categorychecklist form-no-clear">
			<?php
				foreach ( $columns as $key => $label ) {
					?>
					<li>
						<label>
							<input type="checkbox" name="sp_columns[]" value="<?php echo $key; ?>" id="sp_columns_<?php echo $key; ?>" <?php checked( ! is_array( $selected ) || in_array( $key, $selected ) ); ?>>
							<?php echo $label; ?>
						</label>
					</li>
					<?php
				}
			?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array() ) );
	}
}