<?php
/**
 * Event Shortcode
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
 * SP_Meta_Box_Event_Shortcode
 */
class SP_Meta_Box_Event_Shortcode {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$shortcodes = apply_filters(
			'sportspress_event_shortcodes',
			array(
				'event_results'     => esc_attr__( 'Results', 'sportspress' ),
				'event_details'     => esc_attr__( 'Details', 'sportspress' ),
				'event_performance' => esc_attr__( 'Box Score', 'sportspress' ),
				'event_venue'       => esc_attr__( 'Venue', 'sportspress' ),
				'event_officials'   => esc_attr__( 'Officials', 'sportspress' ),
				'event_teams'       => esc_attr__( 'Teams', 'sportspress' ),
				'event_full'        => esc_attr__( 'Full Info', 'sportspress' ),
			)
		);
		if ( $shortcodes ) {
			?>
		<p class="howto">
			<?php esc_attr_e( 'Copy this code and paste it into your post, page or text widget content.', 'sportspress' ); ?>
		</p>
			<?php foreach ( $shortcodes as $id => $label ) { ?>
		<p>
			<strong><?php echo esc_html( $label ); ?></strong>
		</p>
		<p><input type="text" value="<?php sp_shortcode_template( $id, $post->ID ); ?>" readonly="readonly" class="code widefat"></p>
		<?php } ?>
			<?php
		}
	}
}
