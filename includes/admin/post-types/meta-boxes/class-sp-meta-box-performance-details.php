<?php
/**
 * Performance Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.0
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
		
		// Post Meta
		$section = get_post_meta( $post->ID, 'sp_section', true );
		if ( '' === $section ) {
			$section = -1;
		}
		$format = get_post_meta( $post->ID, 'sp_format', true );
		if ( '' === $format ) {
			$format = 'number';
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
		<?php
		if ( 'auto' === get_option( 'sportspress_player_columns', 'auto' ) ) {
			$visible = get_post_meta( $post->ID, 'sp_visible', true );
			if ( '' === $visible ) {
				$visible = 1;
			}
			?>
			<p>
				<strong><?php _e( 'Visible', 'sportspress' ); ?></strong>
				<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Display in player profile?', 'sportspress' ); ?>"></i>
			</p>
			<ul class="sp-visible-selector">
				<li>
					<label class="selectit">
						<input name="sp_visible" id="sp_visible_yes" type="radio" value="1" <?php checked( $visible ); ?>>
						<?php _e( 'Yes', 'sportspress' ); ?>
					</label>
				</li>
				<li>
					<label class="selectit">
						<input name="sp_visible" id="sp_visible_no" type="radio" value="0" <?php checked( ! $visible ); ?>>
						<?php _e( 'No', 'sportspress' ); ?>
					</label>
				</li>
			</ul>
			<?php
		}
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
		update_post_meta( $post_id, 'sp_section', (int) sp_array_value( $_POST, 'sp_section', -1 ) );
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'number' ) );
		if ( 'auto' === get_option( 'sportspress_player_columns', 'auto' ) ) {
			update_post_meta( $post_id, 'sp_visible', sp_array_value( $_POST, 'sp_visible', 1 ) );
		}
	}
}