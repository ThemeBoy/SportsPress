<?php
/**
 * List Format
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_List_Format
 */
class SP_Meta_Box_List_Format {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$the_format = get_post_meta( $post->ID, 'sp_format', true );
		?>
		<div id="post-formats-select">
			<?php foreach ( SP()->formats->list as $key => $format ): ?>
				<input type="radio" name="sp_format" class="post-format" id="post-format-<?php echo $key; ?>" value="<?php echo $key; ?>" <?php checked( true, ( $key == 'list' && ! $the_format ) || $the_format == $key ); ?>> <label for="post-format-<?php echo $key; ?>" class="post-format-icon post-format-<?php echo $key; ?>"><?php echo $format; ?></label><br>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'list' ) );
	}
}