<?php
/**
 * Staff Metrics
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version     2.7.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Staff_Metrics
 */
class SP_Meta_Box_Staff_Metrics {

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
			'meta_query' => array(
				'relation' => 'OR',
				array(
					'key' => 'sp_metric_type',
					'value' => 'staff',
					'compare' => '='
				),
				array(
					'key' => 'sp_metric_type',
					'value' => 'both',
					'compare' => '='
				),
			),
		);

		$vars = get_posts( $args );

		if ( $vars ):
			foreach ( $vars as $var ):
			?>
			<p><strong><?php echo $var->post_title; ?></strong></p>
			<p><input type="text" name="sp_metrics[<?php echo $var->post_name; ?>]" value="<?php echo esc_attr( sp_array_value( $metrics, $var->post_name, '' ) ); ?>" /></p>
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