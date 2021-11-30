<?php
/**
 * Event Details
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
 * SP_Meta_Box_Event_Details
 */
class SP_Meta_Box_Event_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$day        = get_post_meta( $post->ID, 'sp_day', true );
		$taxonomies = get_object_taxonomies( 'sp_event' );
		$minutes    = get_post_meta( $post->ID, 'sp_minutes', true );
		?>
		<?php do_action( 'sportspress_event_details_meta_box', $post ); ?>
		<div class="sp-event-day-field">
			<p><strong><?php esc_attr_e( 'Match Day', 'sportspress' ); ?></strong> <span class="dashicons dashicons-editor-help sp-desc-tip" title="<?php esc_attr_e( 'Optional', 'sportspress' ); ?>"></span></p>
			<p>
				<input name="sp_day" type="text" class="medium-text" placeholder="<?php esc_attr_e( 'Default', 'sportspress' ); ?>" value="<?php echo esc_attr( $day ); ?>">
			</p>
		</div>
		<div class="sp-event-minutes-field">
			<p><strong><?php esc_attr_e( 'Full Time', 'sportspress' ); ?></strong></p>
			<p>
				<input name="sp_minutes" type="number" step="1" min="0" class="small-text" placeholder="<?php echo esc_attr( get_option( 'sportspress_event_minutes', 90 ) ); ?>" value="<?php echo esc_attr( $minutes ); ?>">
				<?php esc_attr_e( 'mins', 'sportspress' ); ?>
			</p>
		</div>
		<?php
		foreach ( $taxonomies as $taxonomy ) {
			if ( 'sp_venue' == $taxonomy ) {
				continue;
			}
			sp_taxonomy_field( $taxonomy, $post, true, true, esc_attr__( 'None', 'sportspress' ) );
		}
		?>
		<div class="sp-event-sp_venue-field">
			<p><strong><?php esc_attr_e( 'Venue', 'sportspress' ); ?></strong></p>
			<p>
				<?php
				$terms = get_the_terms( $post->ID, 'sp_venue' );
				$args  = array(
					'taxonomy'         => 'sp_venue',
					'name'             => 'tax_input[sp_venue][]',
					'class'            => 'sp-has-dummy',
					'selected'         => sp_get_the_term_id_or_meta( $post->ID, 'sp_venue' ),
					'values'           => 'term_id',
					'show_option_none' => esc_attr__( '&mdash; Not set &mdash;', 'sportspress' ),
					'chosen'           => true,
				);
				if ( in_array( 'sp_venue', apply_filters( 'sportspress_event_auto_taxonomies', array( 'sp_venue' ) ) ) ) {
					$args['show_option_all'] = esc_attr__( '(Auto)', 'sportspress' );
				}
				if ( ! sp_dropdown_taxonomies( $args ) ) {
					sp_taxonomy_adder( 'sp_venue', 'sp_event', esc_attr__( 'Add New', 'sportspress' ) );
				}
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_day', sp_array_value( $_POST, 'sp_day', null, 'text' ) );
		update_post_meta( $post_id, 'sp_minutes', sp_array_value( $_POST, 'sp_minutes', get_option( 'sportspress_event_minutes', 90 ), 'int' ) );
		$venues = array_filter( sp_array_value( sp_array_value( $_POST, 'tax_input', array() ), 'sp_venue', array() ) );
		if ( empty( $venues ) ) {
			$teams = sp_array_value( $_POST, 'sp_team', array(), 'int' );
			$team  = reset( $teams );
			$venue = sp_get_the_term_id( $team, 'sp_venue' );
			wp_set_post_terms( $post_id, $venue, 'sp_venue' );
		}
	}
}
