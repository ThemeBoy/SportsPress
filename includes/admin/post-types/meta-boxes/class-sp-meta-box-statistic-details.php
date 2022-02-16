<?php
/**
 * Statistic Details
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'SP_Meta_Box_Config' ) ) {
	require 'class-sp-meta-box-config.php';
}

/**
 * SP_Meta_Box_Statistic_Details
 */
class SP_Meta_Box_Statistic_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$precision = get_post_meta( $post->ID, 'sp_precision', true );
		$section   = get_post_meta( $post->ID, 'sp_section', true );
		$format    = get_post_meta( $post->ID, 'sp_format', true );
		$total     = get_post_meta( $post->ID, 'sp_type', true );
		$visible   = get_post_meta( $post->ID, 'sp_visible', true );

		// Defaults
		if ( '' === $precision ) {
			$precision = 0;
		}
		if ( '' === $section ) {
			$section = -1;
		}
		if ( '' === $format ) {
			$format = 'number';
		}
		if ( '' === $visible ) {
			$visible = 1;
		}
		?>
		<p><strong><?php esc_attr_e( 'Key', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo esc_attr( $post->post_name ); ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo esc_attr( $post->post_name ); ?>">
		</p>
		<p><strong><?php esc_attr_e( 'Decimal Places', 'sportspress' ); ?></strong></p>
		<p class="sp-precision-selector">
			<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo esc_attr( $precision ); ?>" placeholder="0">
		</p>
		<p><strong><?php esc_attr_e( 'Category', 'sportspress' ); ?></strong></p>
		<p class="sp-section-selector">
			<select name="sp_section">
				<?php
				$options = apply_filters(
					'sportspress_performance_sections',
					array(
						-1 => esc_attr__( 'All', 'sportspress' ),
						0  => esc_attr__( 'Offense', 'sportspress' ),
						1  => esc_attr__(
							'Defense',
							'sportspress'
						),
					)
				);
				foreach ( $options as $key => $value ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key == $section, true, false ), esc_attr( $value ) );
				endforeach;
				?>
			</select>
		</p>
		<p><strong><?php esc_attr_e( 'Format', 'sportspress' ); ?></strong></p>
		<p>
			<select name="sp_format">
				<?php
				$options = apply_filters(
					'sportspress_statistic_formats',
					array(
						'number' => esc_attr__( 'Number', 'sportspress' ),
						'time'   => esc_attr__(
							'Time',
							'sportspress'
						),
					)
				);
				foreach ( $options as $key => $value ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key == $format, true, false ), esc_attr( $value ) );
				endforeach;
				?>
			</select>
		</p>
		<p><strong><?php esc_attr_e( 'Type', 'sportspress' ); ?></strong></p>
		<p>
			<select name="sp_type">
				<?php
				$options = apply_filters(
					'sportspress_statistic_total_types',
					array(
						'total'   => esc_attr__( 'Total', 'sportspress' ),
						'average' => esc_attr__(
							'Average',
							'sportspress'
						),
					)
				);
				foreach ( $options as $key => $value ) :
					printf( '<option value="%s" %s>%s</option>', esc_attr( $key ), selected( $key == $total, true, false ), esc_attr( $value ) );
				endforeach;
				?>
			</select>
		</p>
		<p>
			<strong><?php esc_attr_e( 'Visible', 'sportspress' ); ?></strong>
			<i class="dashicons dashicons-editor-help sp-desc-tip" title="<?php esc_attr_e( 'Display in player profile?', 'sportspress' ); ?>"></i>
		</p>
		<ul class="sp-visible-selector">
			<li>
				<label class="selectit">
					<input name="sp_visible" id="sp_visible_yes" type="radio" value="1" <?php checked( $visible ); ?>>
					<?php esc_attr_e( 'Yes', 'sportspress' ); ?>
				</label>
			</li>
			<li>
				<label class="selectit">
					<input name="sp_visible" id="sp_visible_no" type="radio" value="0" <?php checked( ! $visible ); ?>>
					<?php esc_attr_e( 'No', 'sportspress' ); ?>
				</label>
			</li>
		</ul>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
		update_post_meta( $post_id, 'sp_section', (int) sp_array_value( $_POST, 'sp_section', -1, 'int' ) );
		update_post_meta( $post_id, 'sp_type', sp_array_value( $_POST, 'sp_type', 'total', 'key' ) );
		update_post_meta( $post_id, 'sp_format', sp_array_value( $_POST, 'sp_format', 'number', 'int' ) );
		update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1, 'int' ) );
		update_post_meta( $post_id, 'sp_visible', sp_array_value( $_POST, 'sp_visible', 1, 'int' ) );
	}

}
