<?php
/**
 * Post Types Admin
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     1.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Post_Types' ) ) :

/**
 * SP_Admin_Post_Types Class
 */
class SP_Admin_Post_Types {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'include_post_type_handlers' ) );
		add_action( 'save_post', array( $this, 'unflag_post' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	/**
	 * Conditonally load classes and functions only needed when viewing a post type.
	 */
	public function include_post_type_handlers() {
		include_once( 'post-types/class-sp-admin-meta-boxes.php' );
		include_once( 'post-types/class-sp-admin-cpt-result.php' );
		include_once( 'post-types/class-sp-admin-cpt-outcome.php' );
		include_once( 'post-types/class-sp-admin-cpt-performance.php' );
		include_once( 'post-types/class-sp-admin-cpt-column.php' );
		include_once( 'post-types/class-sp-admin-cpt-metric.php' );
		include_once( 'post-types/class-sp-admin-cpt-statistic.php' );
		include_once( 'post-types/class-sp-admin-cpt-event.php' );
		include_once( 'post-types/class-sp-admin-cpt-team.php' );
		include_once( 'post-types/class-sp-admin-cpt-player.php' );
		include_once( 'post-types/class-sp-admin-cpt-staff.php' );
		do_action( 'sportspress_include_post_type_handlers' );
	}

	/**
	 * Unflag preset and sample posts.
	 */
	public function unflag_post( $post_id ) {
		$post_types = sp_post_types();
		$config_types = sp_config_types();
		$post_type = get_post_type( $post_id );
		if ( in_array( $post_type, $post_types ) ):
			delete_post_meta( $post_id, '_sp_sample' );
		elseif ( in_array( $post_type, $config_types ) ):
			delete_post_meta( $post_id, '_sp_preset' );
		endif;
	}

	/**
	 * Change messages when a post type is updated.
	 *
	 * @param  array $messages
	 * @return array
	 */
	public function post_updated_messages( $messages ) {
		global $typenow, $post;

		if ( is_sp_config_type( $typenow ) ):
			$obj = get_post_type_object( $typenow );

			for ( $i = 0; $i <= 10; $i++ ):
				$messages['post'][ $i ] = __( 'Settings saved.', 'sportspress' ) .
					' <a href="' . esc_url( admin_url( 'edit.php?post_type=' . $typenow ) ) . '">' .
					__( 'View All', 'sportspress' ) . '</a>';
			endfor;
		elseif ( is_sp_post_type( $typenow ) ):
			$obj = get_post_type_object( $typenow );

			$messages['post'][1] = __( 'Changes saved.', 'sportspress' ) .
				' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

			$messages['post'][4] = __( 'Changes saved.', 'sportspress' );

			$messages['post'][6] = __( 'Success!', 'sportspress' ) .
				' <a href="' . esc_url( get_permalink($post->ID) ) . '">' . $obj->labels->view_item . '</a>';

			$messages['post'][7] = __( 'Changes saved.', 'sportspress' );

			$messages['post'][8] = __( 'Success!', 'sportspress' ) .
				' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
				sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

			$messages['post'][9] = sprintf(
				__( 'Scheduled for: <b>%1$s</b>.', 'sportspress' ),
				date_i18n( __( 'M j, Y @ G:i', 'sportspress' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ) .
				' <a target="_blank" href="' . esc_url( get_permalink($post->ID) ) . '">' .
				sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';

			$messages['post'][10] = __( 'Success!', 'sportspress' ) .
				' <a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) . '">' .
				sprintf( __( 'Preview %s', 'sportspress' ), $obj->labels->singular_name ) . '</a>';
		endif;

		return $messages;
	}
}

endif;

return new SP_Admin_Post_Types();