<?php
/**
 * Load assets.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     0.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Assets' ) ) :

/**
 * SP_Admin_Assets Class
 */
class SP_Admin_Assets {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles
	 */
	public function admin_styles() {
		global $wp_scripts;

		// Sitewide menu CSS
		wp_enqueue_style( 'sportspress-admin-menu-styles', SP()->plugin_url() . '/assets/css/menu.css', array(), SP_VERSION );

		$screen = get_current_screen();

		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {

			// Admin styles for SP pages only
			wp_enqueue_style( 'sportspress-admin', SP()->plugin_url() . '/assets/css/admin.css', array(), SP_VERSION );
			wp_enqueue_style( 'jquery-chosen', SP()->plugin_url() . '/assets/css/chosen.css', array(), '1.1.0' );
			wp_enqueue_style( 'wp-color-picker' );
		}

		if ( in_array( $screen->id, array( 'dashboard' ) ) ) {
			wp_enqueue_style( 'sportspress-admin-dashboard-styles', SP()->plugin_url() . '/assets/css/dashboard.css', array(), SP_VERSION );
		}

		do_action( 'sportspress_admin_css' );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_scripts() {
		global $wp_query, $post;

		$screen = get_current_screen();

		// Register scripts
		wp_register_script( 'jquery-chosen', SP()->plugin_url() . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );

		wp_register_script( 'jquery-tiptip', SP()->plugin_url() . '/assets/js/jquery.tipTip.min.js', array( 'jquery' ), '1.3', true );

		wp_register_script( 'jquery-caret', SP()->plugin_url() . '/assets/js/jquery.caret.min.js', array( 'jquery' ), '1.02', true );

		wp_register_script( 'jquery-countdown', SP()->plugin_url() . '/assets/js/jquery.countdown.min.js', array( 'jquery' ), '2.0.2', true );

		wp_register_script( 'google-maps', 'http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places' );

		wp_register_script( 'jquery-locationpicker', SP()->plugin_url() . '/assets/js/locationpicker.jquery.js', array( 'jquery', 'google-maps' ), '0.1.6', true );

		wp_register_script( 'sportspress-admin-locationpicker', SP()->plugin_url() . '/assets/js/admin-locationpicker.js', array( 'jquery', 'google-maps', 'jquery-locationpicker' ), SP_VERSION, true );
	
		wp_register_script( 'sportspress-admin', SP()->plugin_url() . '/assets/js/admin.js', array( 'jquery', 'jquery-chosen', 'jquery-tiptip', 'jquery-caret', 'jquery-countdown' ), SP_VERSION, true );

		// SportsPress admin pages
	    if ( in_array( $screen->id, sp_get_screen_ids() ) ) {

	    	wp_enqueue_script( 'jquery' );
	    	wp_enqueue_script( 'jquery-chosen' );
	    	wp_enqueue_script( 'jquery-tiptip' );
	    	wp_enqueue_script( 'jquery-caret' );
	    	wp_enqueue_script( 'jquery-countdown' );
	    	wp_enqueue_script( 'sportspress-admin' );

	    	$params = array(
				'none' => __( 'None', 'sportspress' ),
				'remove_text' => __( '&mdash; Remove &mdash;', 'sportspress' ),
				'days' => __( 'days', 'sportspress' ),
				'hrs' => __( 'hrs', 'sportspress' ),
				'mins' => __( 'mins', 'sportspress' ),
				'secs' => __( 'secs', 'sportspress' )
	    	);

	    	// Localize scripts
			wp_localize_script( 'sportspress-admin', 'localized_strings', $params );
	    }

	    // Edit venue pages
	    if ( in_array( $screen->id, array( 'edit-sp_venue' ) ) ) {

	    	wp_enqueue_script( 'google-maps' );
	    	wp_enqueue_script( 'jquery-locationpicker' );
	    	wp_enqueue_script( 'sportspress-admin-locationpicker' );

		}
	}
}

endif;

return new SP_Admin_Assets();
