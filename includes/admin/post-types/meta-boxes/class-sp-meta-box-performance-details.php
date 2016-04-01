<?php
/**
 * Performance Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.19
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Config' ) )
	include( 'class-sp-meta-box-config.php' );

/**
 * SP_Meta_Box_Performance_Details
 */
class SP_Meta_Box_Performance_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		global $pagenow;
		if ( 'post.php' == $pagenow && 'draft' !== get_post_status() ) {
			$readonly = true;
		} else {
			$readonly = false;
		}

		// Options
		$visibility_options = apply_filters( 'sportspress_statistic_visibility_options', array( 'sp_event', 'sp_player' ) );
		
		// Post Meta
		$section = get_post_meta( $post->ID, 'sp_section', true );
		if ( '' === $section ) {
			$section = -1;
		}
		$format = get_post_meta( $post->ID, 'sp_format', true );
		if ( '' === $format ) {
			$format = 'number';
		}
		$visibility = get_post_meta( $post->ID, 'sp_visibility', true );
		if ( ! is_array( $visibility ) ) {
			$visibility = $visibility_options;
		}
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>"<?php if ( $readonly ) { ?> readonly="readonly"<?php } ?>>
		</p>
		<p><strong><?php _e( 'Category', 'sportspress' ); ?></strong></p>
		<p class="sp-section-selector">
			<select name="sp_section">
				<?php
				$options = apply_filters( 'sportspress_performance_sections', array( -1 => __( 'All', 'sportspress' ), 0 => __( 'Offense', 'sportspress' ), 1 => __( 'Defense', 'sportspress' ) ) );
				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( $key == $section, true, false ), $value );
				endforeach;
				?>
			</select>
		</p>
		<p><strong><?php _e( 'Format', 'sportspress' ); ?></strong></p>
		<p class="sp-format-selector">
			<select name="sp_format">
				<?php
				$options = apply_filters( 'sportspress_performance_formats', array( 'number' => __( 'Number', 'sportspress' ), 'text' => __( 'Text', 'sportspress' ) ) );
				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( $key == $format, true, false ), $value );
				endforeach;
				?>
			</select>
		</p>
		<p><strong><?php _e( 'Visibility', 'sportspress' ); ?></strong></p>
		<ul class="categorychecklist form-no-clear">
			<?php foreach ( $visibility_options as $option ) { $object = get_post_type_object( $option ); ?>
			<li>
				<label class="selectit">
					<input name="sp_visibility[]" id="sp_visibility_<?php echo $option; ?>" type="checkbox" value="<?php echo $option; ?>" <?php checked( in_array( $option, $visibility ) ); ?>>
					<?php echo $object->labels->singular_name; ?>
				</label>
			</li>
			<?php } ?>
		</ul>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
		update_post_meta( $post_id, 'sp_section', (int) sp_array_value( $_POST, 'sp_section', -1 ) );
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'number' ) );
		update_post_meta( $post_id, 'sp_visibility', (array) sp_array_value( $_POST, 'sp_visibility', array() ) );
	}
}