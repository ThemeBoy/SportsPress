<?php
/**
 * Event Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.4.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Details
 */
class SP_Meta_Box_Event_Details {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$minutes = get_post_meta( $post->ID, 'sp_minutes', true );
		$taxonomies = apply_filters( 'sportspress_event_taxonomies', array( 'sp_league' => null, 'sp_season' => null, 'sp_venue' => 'sp_event' ) );
		?>
		<div class="sp-event-minutes-field">
			<p><strong><?php _e( 'Full Time', 'sportspress' ); ?></strong></p>
			<p>
				<input name="sp_minutes" type="number" step="1" min="0" class="small-text" placeholder="<?php echo get_option( 'sportspress_event_minutes', 90 ); ?>" value="<?php echo $minutes; ?>">
				<?php _e( 'mins', 'sportspress' ); ?>
			</p>
		</div>
		<?php
		foreach ( $taxonomies as $taxonomy => $post_type ) {
			$obj = get_taxonomy( $taxonomy ); if ( $obj ) {
				?>
				<div class="sp-event-<?php echo $taxonomy; ?>-field">
					<p><strong><?php echo $obj->labels->singular_name; ?></strong></p>
					<p>
						<?php
						$args = array(
							'taxonomy' => $taxonomy,
							'name' => $taxonomy,
							'class' => 'sp-has-dummy',
							'selected' => sp_get_the_term_id_or_meta( $post->ID, $taxonomy ),
							'values' => 'term_id',
							'show_option_none' => __( '-- Not set --', 'sportspress' ),
						);
						if ( in_array( $taxonomy, apply_filters( 'sportspress_event_auto_taxonomies', array( 'sp_venue' ) ) ) ) {
							$args['show_option_all'] = __( '(Auto)', 'sportspress' );
						}
						if ( ! sp_dropdown_taxonomies( $args ) ) {
							sp_taxonomy_adder( $taxonomy, $post_type, $obj->labels->add_new_item );
						}
						?>
					</p>
				</div>
			<?php
			}
		}
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_minutes', sp_array_value( $_POST, 'sp_minutes', get_option( 'sportspress_event_minutes', 90 ) ) );
		$taxonomies = apply_filters( 'sportspress_event_taxonomies', array( 'sp_league' => null, 'sp_season' => null, 'sp_venue' => 'sp_event' ) );
		foreach ( $taxonomies as $taxonomy => $post_type ) {
			$value = sp_array_value( $_POST, $taxonomy, -1 );
			if ( 0 == $value) {
				$teams = sp_array_value( $_POST, 'sp_team', array() );
				$team = reset( $teams );
				$value = sp_get_the_term_id( $team, $taxonomy );
			}
			wp_set_post_terms( $post_id, $value, $taxonomy );
			update_post_meta( $post_id, $taxonomy, $value );
		}
	}
}
