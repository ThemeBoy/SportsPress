<?php
/**
 * Calendar Feeds
 *
 * Based on a tutorial by Steve Thomas.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Calendar_Feeds
 */
class SP_Meta_Box_Calendar_Feeds {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$feeds = new SP_Feeds();
		$calendar_feeds = $feeds->calendar;
		?>
		<div>
			<?php foreach ( $calendar_feeds as $slug => $name ) { ?>
				<?php $link = add_query_arg( 'feed', 'sp-calendar-' . $slug, get_post_permalink( $post ) ); ?>
				<p>
					<strong><?php echo $name; ?></strong>
					<a class="sp-link" href="<?php echo $link; ?>" target="_blank" title="<?php _e( 'Link', 'sportspress' ); ?>"></a>
				</p>
				<p>
					<input type="text" value="<?php echo $link; ?>" readonly="readonly" class="code widefat">
				</p>
			<?php } ?>
		</div>
		<?php
	}
}