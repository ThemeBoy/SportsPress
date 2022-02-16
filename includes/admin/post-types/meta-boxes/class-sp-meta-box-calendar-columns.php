<?php
/**
 * Calendar Columns
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_Calendar_Columns
 */
class SP_Meta_Box_Calendar_Columns {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$selected     = (array) get_post_meta( $post->ID, 'sp_columns', true );
		$title_format = get_option( 'sportspress_event_list_title_format', 'title' );
		$time_format  = get_option( 'sportspress_event_list_time_format', 'combined' );

		if ( is_array( $selected ) ) {
			$selected = array_filter( $selected );
		}

		$columns = array();

		if ( 'teams' === $title_format ) {
			$columns['event'] = esc_attr__( 'Home', 'sportspress' ) . ' | ' . esc_attr__( 'Away', 'sportspress' );
		} elseif ( 'homeaway' === $title_format ) {
			$columns['event'] = esc_attr__( 'Teams', 'sportspress' );
		} else {
			$columns['event'] = esc_attr__( 'Title', 'sportspress' );
		}

		if ( 'time' === $time_format || 'separate' === $time_format ) {
			$columns['time'] = esc_attr__( 'Time', 'sportspress' );
		} elseif ( 'combined' === $time_format ) {
			$columns['time'] = esc_attr__( 'Time/Results', 'sportspress' );
		}

		if ( 'results' === $time_format || 'separate' === $time_format ) {
			$columns['results'] = esc_attr__( 'Results', 'sportspress' );
		}

		$columns['league']  = esc_attr__( 'League', 'sportspress' );
		$columns['season']  = esc_attr__( 'Season', 'sportspress' );
		$columns['venue']   = esc_attr__( 'Venue', 'sportspress' );
		$columns['article'] = esc_attr__( 'Article', 'sportspress' );
		$columns['day']     = esc_attr__( 'Match Day', 'sportspress' );

		$columns = apply_filters( 'sportspress_calendar_columns', $columns );
		?>
		<div class="sp-instance">
			<ul class="categorychecklist form-no-clear">
			<?php
			foreach ( $columns as $key => $label ) {
				?>
					<li>
						<label>
							<input type="checkbox" name="sp_columns[]" value="<?php echo esc_attr( $key ); ?>" id="sp_columns_<?php echo esc_attr( $key ); ?>" <?php checked( ! is_array( $selected ) || in_array( $key, $selected ) ); ?>>
						<?php echo esc_html( $label ); ?>
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
		update_post_meta( $post_id, 'sp_columns', sp_array_value( $_POST, 'sp_columns', array(), 'text' ) );
	}
}
