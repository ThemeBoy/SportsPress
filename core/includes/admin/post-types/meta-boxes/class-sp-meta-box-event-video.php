<?php
/**
 * Event Video
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Video
 */
class SP_Meta_Box_Event_Video {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		$video = get_post_meta( $post->ID, 'sp_video', true );
		if ( $video ):
		?>
		<fieldset class="sp-video-embed">
			<?php echo apply_filters( 'the_content', '[embed width="254"]' . $video . '[/embed]' ); ?>
			<p><a href="#" class="sp-remove-video"><?php _e( 'Remove video', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php endif; ?>
		<fieldset class="sp-video-field hidden">
			<p><strong><?php _e( 'URL', 'sportspress' ); ?></strong></p>
			<p><input class="widefat" type="text" name="sp_video" id="sp_video" value="<?php echo $video; ?>"></p>
			<p><a href="#" class="sp-remove-video"><?php _e( 'Cancel', 'sportspress' ); ?></a></p>
		</fieldset>
		<fieldset class="sp-video-adder<?php if ( $video ): ?> hidden<?php endif; ?>">
			<p><a href="#" class="sp-add-video"><?php _e( 'Add video', 'sportspress' ); ?></a></p>
		</fieldset>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_video', sp_array_value( $_POST, 'sp_video', null ) );
	}
}