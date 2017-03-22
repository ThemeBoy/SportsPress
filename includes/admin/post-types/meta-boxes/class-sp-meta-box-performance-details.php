<?php
/**
 * Performance Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.3
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
		$singular = get_post_meta( $post->ID, 'sp_singular', true );
		$section = get_post_meta( $post->ID, 'sp_section', true );
		if ( '' === $section ) {
			$section = -1;
		}
		$format = get_post_meta( $post->ID, 'sp_format', true );
		if ( '' === $format ) {
			$format = 'number';
		}
		$precision = get_post_meta( $post->ID, 'sp_precision', true );
		if ( '' === $precision ) {
			$precision = 0;
		}
		$timed = get_post_meta( $post->ID, 'sp_timed', true );
		if ( '' === $timed ) {
			$timed = true;
		}
		$sendoff = get_post_meta( $post->ID, 'sp_sendoff', true );
		if ( '' === $sendoff ) {
			$sendoff = false;
		}
		?>
		<p><strong><?php _e( 'Variable', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>"<?php if ( $readonly ) { ?> readonly="readonly"<?php } ?>>
		</p>
		<p><strong><?php _e( 'Singular', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_singular" type="text" id="sp_singular" placeholder="<?php echo $post->post_title; ?>" value="<?php echo $singular; ?>">
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
				$options = apply_filters( 'sportspress_performance_formats', array( 'number' => __( 'Number', 'sportspress' ), 'time' => __( 'Time', 'sportspress' ), 'text' => __( 'Text', 'sportspress' ), 'equation' => __( 'Equation', 'sportspress' ) ) );
				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( $key == $format, true, false ), $value );
				endforeach;
				?>
			</select>
		</p>
		<div id="sp_precisiondiv">
			<p><strong><?php _e( 'Decimal Places', 'sportspress' ); ?></strong></p>
			<p>
				<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>" placeholder="0">
			</p>
		</div>
		<div id="sp_timeddiv">
			<p>
				<strong><?php _e( 'Timed', 'sportspress' ); ?></strong>
				<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( 'Record minutes?', 'sportspress' ); ?>"></i>
			</p>
			<ul class="sp-timed-selector">
				<li>
					<label class="selectit">
						<input name="sp_timed" id="sp_timed_yes" type="radio" value="1" <?php checked( $timed ); ?>>
						<?php _e( 'Yes', 'sportspress' ); ?>
					</label>
				</li>
				<li>
					<label class="selectit">
						<input name="sp_timed" id="sp_timed_no" type="radio" value="0" <?php checked( ! $timed ); ?>>
						<?php _e( 'No', 'sportspress' ); ?>
					</label>
				</li>
			</ul>
		</div>
		<div id="sp_sendoffdiv">
			<p>
				<strong><?php _e( 'Send Off', 'sportspress' ); ?></strong>
				<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php _e( "Don't count minutes after?", 'sportspress' ); ?>"></i>
			</p>
			<ul class="sp-sendoff-selector">
				<li>
					<label class="selectit">
						<input name="sp_sendoff" id="sp_sendoff_yes" type="radio" value="1" <?php checked( $sendoff ); ?>>
						<?php _e( 'Yes', 'sportspress' ); ?>
					</label>
				</li>
				<li>
					<label class="selectit">
						<input name="sp_sendoff" id="sp_sendoff_no" type="radio" value="0" <?php checked( ! $sendoff ); ?>>
						<?php _e( 'No', 'sportspress' ); ?>
					</label>
				</li>
			</ul>
		</div>
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
		update_post_meta( $post_id, 'sp_singular', sp_array_value( $_POST, 'sp_singular', '' ) );
		update_post_meta( $post_id, 'sp_section', (int) sp_array_value( $_POST, 'sp_section', -1 ) );
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'number' ) );
		update_post_meta( $post_id, 'sp_precision', sp_array_value( $_POST, 'sp_precision', 0 ) );
		update_post_meta( $post_id, 'sp_timed', sp_array_value( $_POST, 'sp_timed', 0 ) );
		update_post_meta( $post_id, 'sp_sendoff', sp_array_value( $_POST, 'sp_sendoff', 0 ) );
		if ( 'auto' === get_option( 'sportspress_player_columns', 'auto' ) ) {
			update_post_meta( $post_id, 'sp_visible', sp_array_value( $_POST, 'sp_visible', 1 ) );
		}
	}
}