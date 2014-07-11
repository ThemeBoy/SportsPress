<?php
/**
 * Addons Page
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Addons' ) ) :

/**
 * SP_Admin_Addons Class
 */
class SP_Admin_Addons {

	/**
	 * Handles output of the reports page in admin.
	 */
	public function output() {

		$view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : '';

		if ( false === ( $addons = get_transient( 'sportspress_addons_html_' . $view ) ) ) {

			$raw_addons = wp_remote_get( 'http://themeboy.com/sportspress/extensions/' . $view . '?orderby=popularity', array(
					'user-agent' => 'sportspress-addons-page',
					'timeout'    => 3
				) );

			if ( ! is_wp_error( $raw_addons ) ) {

				$raw_addons = wp_remote_retrieve_body( $raw_addons );

				// Get Products
				$dom = new DOMDocument();
				libxml_use_internal_errors(true);
				$dom->loadHTML( $raw_addons );

				$addons = '';
				$xpath  = new DOMXPath( $dom );
				$tags   = $xpath->query('//ul[@class="products"]');
				foreach ( $tags as $tag ) {
					$addons = $tag->ownerDocument->saveXML( $tag );
					break;
				}

				if ( $addons )
					set_transient( 'sportspress_addons_html_' . $view, wp_kses_post( $addons ), 60*60*24*7 ); // Cached for a week
			}
		}

//		include_once( 'views/html-admin-page-addons.php' );
	}
}

endif;

return new SP_Admin_Addons();