<?php
/**
 * Load assets.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version   2.6.15
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
	public function admin_styles( $hook ) {
		// Sitewide menu CSS
		wp_enqueue_style( 'sportspress-admin-menu-styles', SP()->plugin_url() . '/assets/css/menu.css', array(), SP_VERSION );

		$screen = get_current_screen();
		
		if ( in_array( $screen->id, sp_get_screen_ids() ) ) {
			// Admin styles for SP pages only
			wp_enqueue_style( 'jquery-chosen', SP()->plugin_url() . '/assets/css/chosen.css', array(), '1.1.0' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'sportspress-admin', SP()->plugin_url() . '/assets/css/admin.css', array(), SP_VERSION );
		} elseif ( strpos( $screen->id, 'sportspress-config' ) !== false ) {
			wp_enqueue_style( 'sportspress-admin', SP()->plugin_url() . '/assets/css/admin.css', array(), SP_VERSION );
		}

		if ( strpos( $screen->id, 'sportspress-overview' ) !== false ) {
			wp_enqueue_style( 'sportspress-admin-slickmap', SP()->plugin_url() . '/assets/css/slickmap.css', array(), '1.1.0' );
		}

		if ( in_array( $screen->id, array( 'dashboard' ) ) ) {
			wp_enqueue_style( 'sportspress-admin-dashboard-styles', SP()->plugin_url() . '/assets/css/dashboard.css', array(), SP_VERSION );
		}

		if ( in_array( $screen->id, array( 'customize' ) ) ) {
			wp_enqueue_style( 'sportspress-admin-customize-styles', SP()->plugin_url() . '/assets/css/customize.css', array(), SP_VERSION );
		}

		if ( in_array( $screen->id, array( 'sp_result', 'sp_performance', 'sp_column', 'sp_statistic' ) ) ) {
			wp_enqueue_style( 'sportspress-admin-equation-styles', SP()->plugin_url() . '/assets/css/equation.css', array(), SP_VERSION );
		}

		if ( in_array( $screen->id, apply_filters( 'sportspress_admin_datepicker_screen_ids', array( 'sp_calendar', 'sp_table', 'sp_list', 'widgets' ) ) ) || in_array( $hook, array('post.php', 'post-new.php') ) ) {
		    wp_enqueue_style( 'jquery-ui-style' , '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css' ); 
			wp_enqueue_style( 'sportspress-admin-datepicker-styles', SP()->plugin_url() . '/assets/css/datepicker.css', array( 'jquery-ui-style' ), SP_VERSION );
		}

		do_action( 'sportspress_admin_css', $screen );
	}

	/**
	 * Enqueue scripts
	 */
	public function admin_scripts($hook) {
		global $wp_query, $post;

		$screen = get_current_screen();

		// Register scripts
		wp_register_script( 'chosen', SP()->plugin_url() . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.1.0', true );

		wp_register_script( 'jquery-tiptip', SP()->plugin_url() . '/assets/js/jquery.tipTip.min.js', array( 'jquery' ), '1.3', true );

		wp_register_script( 'jquery-caret', SP()->plugin_url() . '/assets/js/jquery.caret.min.js', array( 'jquery' ), '1.02', true );

		wp_register_script( 'jquery-fitvids', SP()->plugin_url() . '/assets/js/jquery.fitvids.js', array( 'jquery' ), '1.1', true );

		wp_register_script( 'sportspress-admin-equationbuilder', SP()->plugin_url() . '/assets/js/admin/equationbuilder.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable' ), SP_VERSION, true );

		wp_register_script( 'sportspress-admin-colorpicker', SP()->plugin_url() . '/assets/js/admin/colorpicker.js', array( 'jquery', 'wp-color-picker', 'iris' ), SP_VERSION, true );

		wp_register_script( 'sportspress-admin-widgets', SP()->plugin_url() . '/assets/js/admin/widgets.js', array( 'jquery' ), SP_VERSION, true );

		wp_register_script( 'sportspress-admin-quickeditor', SP()->plugin_url() . '/assets/js/admin/quickeditor.js', array( 'jquery' ), SP_VERSION, true );

		// SportsPress admin pages
	    if ( in_array( $screen->id, sp_get_screen_ids() ) || strpos( $screen->id, 'sportspress-config' ) || in_array($hook, array('post.php', 'post-new.php') ) ) {

	    	wp_enqueue_script( 'jquery' );
	    	wp_enqueue_script( 'chosen' );
	    	wp_enqueue_script( 'jquery-ui-core' );
	    	wp_enqueue_script( 'jquery-ui-draggable' );
	    	wp_enqueue_script( 'jquery-ui-droppable' );
	    	wp_enqueue_script( 'jquery-ui-sortable' );
	    	wp_enqueue_script( 'jquery-tiptip' );
	    	wp_enqueue_script( 'jquery-caret' );
	    	wp_enqueue_script( 'jquery-fitvids' );
	    	wp_enqueue_script( 'sportspress-admin', SP()->plugin_url() . '/assets/js/admin/sportspress-admin.js', array( 'jquery', 'chosen', 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-tiptip', 'jquery-caret', 'jquery-fitvids' ), SP_VERSION, true );
	    	
	    	$strings = apply_filters( 'sportspress_localized_strings', array(
				'none' => __( 'None', 'sportspress' ),
				'remove_text' => __( '&mdash; Remove &mdash;', 'sportspress' ),
				'days' => __( 'days', 'sportspress' ),
				'hrs' => __( 'hrs', 'sportspress' ),
				'mins' => __( 'mins', 'sportspress' ),
				'secs' => __( 'secs', 'sportspress' ),
				'displaying_posts' => html_entity_decode( __( 'Displaying %s&#8211;%s of %s', 'sportspress' ) ),
	    	) );

	    	// Localize scripts
			wp_localize_script( 'sportspress-admin', 'localized_strings', $strings );
	    }

		if ( in_array( $screen->id, array( 'widgets' ) ) ) {
	    	wp_enqueue_script( 'sportspress-admin-widgets' );
		}

		// Edit color
		if ( in_array( $screen->id, array( 'sp_outcome' ) ) ) {
	    	wp_enqueue_script( 'sportspress-admin-colorpicker' );
		}

		// Edit equation
		if ( in_array( $screen->id, array( 'sp_result', 'sp_performance', 'sp_column', 'sp_statistic' ) ) ) {
	    	wp_enqueue_script( 'sportspress-admin-equationbuilder' );
		}

		// Quick edit
		if ( in_array( $screen->id, array( 'edit-sp_player' ) ) ) {
			wp_enqueue_script( 'sportspress-admin-quickeditor' );
		}
	}
}

endif;

return new SP_Admin_Assets();
