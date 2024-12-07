<?php
/**
 * Staff Profile Shortcode
 *
 * @author      ThemeBoy
 * @category    Shortcodes
 * @package     SportsPress/Shortcodes/Staff
 * @version     2.7.23
 */
class SP_Shortcode_Staff_Profile {

	/**
	 * Output the staff shortcode.
	 *
	 * @param array $atts
	 */
	public static function output( $atts ) {

		if ( ! isset( $atts['id'] ) && isset( $atts[0] ) && is_numeric( $atts[0] ) ) {
			$atts['id'] = $atts[0];
		}

		sp_get_template( 'staff-header.php', $atts );
		sp_get_template( 'staff-photo.php', $atts );
		sp_get_template( 'staff-details.php', $atts );
		sp_get_template( 'staff-excerpt.php', $atts );
		sp_get_template( 'staff-content.php', $atts );

		/**
		 * Action hook to allow loading additional templates or content.
		 *
		 * @param array $atts Shortcode attributes.
		 */
		do_action( 'sp_staff_profile_after_templates', $atts );
	}
}
