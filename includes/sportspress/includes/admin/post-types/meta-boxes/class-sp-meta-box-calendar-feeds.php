<?php
/**
 * Calendar Feeds
 *
 * Based on a tutorial by Steve Thomas.
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
 * SP_Meta_Box_Calendar_Feeds
 */
class SP_Meta_Box_Calendar_Feeds {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$feeds          = new SP_Feeds();
		$calendar_feeds = $feeds->calendar;
		?>
		<div>
			<?php foreach ( $calendar_feeds as $slug => $formats ) { ?>
				<?php $link = add_query_arg( 'feed', 'sp-' . $slug, untrailingslashit( get_post_permalink( $post ) ) ); ?>
				<?php foreach ( $formats as $format ) { ?>
					<?php
					$protocol = sp_array_value( $format, 'protocol' );
					if ( $protocol ) {
						$feed = str_replace( array( 'http:', 'https:' ), 'webcal:', $link );
					} else {
						$feed = $link;
					}
					$prefix = sp_array_value( $format, 'prefix' );
					if ( $prefix ) {
						$feed = $prefix . urlencode( $feed );
					}
					?>
					<p>
						<strong><?php echo esc_html( sp_array_value( $format, 'name' ) ); ?></strong>
						<a class="sp-link" href="<?php echo esc_attr( $feed ); ?>" target="_blank" title="<?php esc_attr_e( 'Link', 'sportspress' ); ?>"></a>
					</p>
					<p>
						<input type="text" value="<?php echo esc_attr( $feed ); ?>" readonly="readonly" class="code widefat">
					</p>
				<?php } ?>
			<?php } ?>
		</div>
		<?php
	}
}
