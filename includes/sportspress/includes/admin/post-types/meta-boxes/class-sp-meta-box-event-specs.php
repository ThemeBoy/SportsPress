<?php
/**
 * Event Specs
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version		2.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * SP_Meta_Box_Event_Specs
 */
class SP_Meta_Box_Event_Specs {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {

		$metrics = get_post_meta( $post->ID, 'sp_specs', true );

		$args = array(
			'post_type' => 'sp_spec',
			'numberposts' => -1,
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		);

		$vars = get_posts( $args );

		if ( $vars ):
			foreach ( $vars as $var ):
			?>
			<p><strong><?php echo $var->post_title; ?></strong></p>
			<p><input type="text" name="sp_specs[<?php echo $var->post_name; ?>]" value="<?php echo esc_attr( sp_array_value( $metrics, $var->post_name, '' ) ); ?>" /></p>
			<?php
			endforeach;
		else:
			sp_post_adder( 'sp_spec', __( 'Add New', 'sportspress' ) );
		endif;
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_specs', sp_array_value( $_POST, 'sp_specs', array() ) );
	}
}