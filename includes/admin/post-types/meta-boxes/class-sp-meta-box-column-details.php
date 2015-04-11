<?php
/**
 * Column Details
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Meta_Box_Config' ) )
	include( 'class-sp-meta-box-config.php' );

/**
 * SP_Meta_Box_Column_Details
 */
class SP_Meta_Box_Column_Details extends SP_Meta_Box_Config {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$equation = explode( ' ', get_post_meta( $post->ID, 'sp_equation', true ) );
		$order = get_post_meta( $post->ID, 'sp_order', true );
		$priority = get_post_meta( $post->ID, 'sp_priority', true );
		$precision = get_post_meta( $post->ID, 'sp_precision', true );

		// Defaults
		if ( $precision == '' ) $precision = 0;
		?>
		<p><strong><?php _e( 'Key', 'sportspress' ); ?></strong></p>
		<p>
			<input name="sp_default_key" type="hidden" id="sp_default_key" value="<?php echo $post->post_name; ?>">
			<input name="sp_key" type="text" id="sp_key" value="<?php echo $post->post_name; ?>">
		</p>
		<p><strong><?php _e( 'Decimal Places', 'sportspress' ); ?></strong></p>
		<p class="sp-precision-selector">
			<input name="sp_precision" type="text" size="4" id="sp_precision" value="<?php echo $precision; ?>" placeholder="0">
		</p>
		<p><strong><?php _e( 'Sort Order', 'sportspress' ); ?></strong></p>
		<p class="sp-order-selector">
			<select name="sp_priority">
				<?php
				$options = array( '0' => __( 'Disable', 'sportspress' ) );
				$count = wp_count_posts( 'sp_column' );
				for( $i = 1; $i <= $count->publish; $i++ ):
					$options[ $i ] = $i;
				endfor;
				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $priority, false ), $value );
				endforeach;
				?>
			</select>
			<select name="sp_order">
				<?php
				$options = array( 'DESC' => __( 'Descending', 'sportspress' ), 'ASC' => __( 'Ascending', 'sportspress' ) );
				foreach ( $options as $key => $value ):
					printf( '<option value="%s" %s>%s</option>', $key, selected( true, $key == $order, false ), $value );
				endforeach;
				?>
			</select>
		</p>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		self::delete_duplicate( $_POST );
		update_post_meta( $post_id, 'sp_precision', (int) sp_array_value( $_POST, 'sp_precision', 1 ) );
		update_post_meta( $post_id, 'sp_priority', sp_array_value( $_POST, 'sp_priority', '0' ) );
		update_post_meta( $post_id, 'sp_order', sp_array_value( $_POST, 'sp_order', 'DESC' ) );
	}
}