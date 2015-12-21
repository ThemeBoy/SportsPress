<?php
/**
 * Player Metrics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     1.9.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Player_Metrics
 */
class SP_Meta_Box_Player_Metrics {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {

		$metrics = get_post_meta( $post->ID, 'sp_metrics', true );

		$args = array(
			'post_type' => 'sp_metric',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$vars = get_posts( $args );

		if ( $vars ):
			foreach ( $vars as $var ):
			$prepend = get_post_meta( $var->ID, 'sp_prepend', true );
			$append = get_post_meta( $var->ID, 'sp_append', true );
			?>
			<p><strong><?php echo $var->post_title; ?></strong></p>
			<p>
				<?php echo ( $prepend ? '<span class="description">' . $prepend . '</span>' : '' ); ?>
				<input type="text" name="sp_metrics[<?php echo $var->post_name; ?>]" value="<?php echo esc_attr( sp_array_value( $metrics, $var->post_name, '' ) ); ?>" />
				<?php echo ( $append ? '<span class="description">' . $append . '</span>' : '' ); ?>
			</p>
			<?php
			endforeach;
		else:
			sp_post_adder( 'sp_metric', __( 'Add New', 'sportspress' ) );
		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_metrics', sp_array_value( $_POST, 'sp_metrics', array() ) );
	}
}