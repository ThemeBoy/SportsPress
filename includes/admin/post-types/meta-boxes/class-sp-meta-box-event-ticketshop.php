<?php
/**
 * Event Ticketshop
 *
 * @author      ThemeBoy
 * @category    Admin
 * @package     SportsPress/Admin/Meta_Boxes
 * @version     2.7.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SP_Meta_Box_Event_Ticketshop
 */
class SP_Meta_Box_Event_Ticketshop {

	/**
	 * Output the metabox
	 */
	public static function output( $post ) {
		wp_nonce_field( 'sportspress_save_data', 'sportspress_meta_nonce' );
		$ticketshop_link = get_post_meta( $post->ID, 'sp_ticketshop_link', true );

		?>
        <div class="sp-event-ticketshop">
            <p><strong><?php esc_attr_e( 'Ticketshop link', 'sportspress' ); ?></strong> <span class="dashicons dashicons-editor-help sp-desc-tip" title="<?php esc_attr_e( 'A link to an external ticket shop.', 'sportspress' ); ?>"></span></p>
            <p>
                <input name="sp_ticketshop_link" type="text" value="<?php echo esc_attr( $ticketshop_link ); ?>">
            </p>
        </div>
		<?php
	}

	/**
	 * Save meta box data
	 */
	public static function save( $post_id, $post ) {
		update_post_meta( $post_id, 'sp_ticketshop_link', sp_array_value( $_POST, 'sp_ticketshop_link', '', 'text' ) );
	}
}
