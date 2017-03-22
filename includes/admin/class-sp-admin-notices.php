<?php
/**
 * Display notices in admin.
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin
 * @version     2.3
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'SP_Admin_Notices' ) ) :

/**
 * SP_Admin_Notices Class
 */
class SP_Admin_Notices {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'switch_theme', array( $this, 'reset_admin_notices' ) );
		add_action( 'sportspress_updated', array( $this, 'reset_admin_notices' ) );
		add_action( 'admin_print_styles', array( $this, 'add_notices' ) );
	}

	/**
	 * Reset notices for themes when switched or a new version of SP is installed
	 */
	public function reset_admin_notices() {
		update_option( 'sportspress_admin_notices', array( 'template_files', 'theme_support' ) );
	}

	/**
	 * Add notices + styles if needed.
	 */
	public function add_notices() {
		$screen = get_current_screen();
		$notices = get_option( 'sportspress_admin_notices', array() );

		if ( ! is_object( $screen ) ) return;

		if ( ! get_option( 'sportspress_completed_setup' ) && ! in_array( $screen->id, array( 'dashboard_page_sp-about', 'dashboard_page_sp-credits', 'dashboard_page_sp-translators' ) ) ) {
			wp_enqueue_style( 'sportspress-activation', plugins_url( '/assets/css/activation.css', SP_PLUGIN_FILE ) );
			add_action( 'admin_notices', array( $this, 'setup_notice' ) );
		}

		if ( 'post' == $screen->base ) {
			$post_id = get_the_ID();
			if ( ! apply_filters( 'sportspress_user_can', current_user_can( 'edit_post', $post_id  ), $post_id ) ) {
				add_action( 'admin_notices', array( $this, 'no_access_notice' ) );
			}
		}

		if ( ! empty( $_GET['hide_theme_support_notice'] ) ) {
			$notices = array_diff( $notices, array( 'theme_support' ) );
			update_option( 'sportspress_admin_notices', $notices );
		}

		if ( ! empty( $_GET['hide_template_files_notice'] ) ) {
			$notices = array_diff( $notices, array( 'template_files' ) );
			update_option( 'sportspress_admin_notices', $notices );
		}

		if ( in_array( 'theme_support', $notices ) && ! current_theme_supports( 'sportspress' ) && ! in_array( $screen->id, array( 'toplevel_page_sportspress', 'dashboard_page_sp-about', 'dashboard_page_sp-credits', 'dashboard_page_sp-translators' ) ) ) {
			$template = get_option( 'template' );

			if ( ! in_array( $template, array( 'twentyfifteen', 'twentyfourteen', 'twentythirteen', 'twentyeleven', 'twentytwelve', 'twentyten' ) ) ) {
				wp_enqueue_style( 'sportspress-activation', plugins_url(  '/assets/css/activation.css', SP_PLUGIN_FILE ) );
				add_action( 'admin_notices', array( $this, 'theme_check_notice' ) );
			}
		}

		if ( in_array( 'template_files', $notices ) ) {
			wp_enqueue_style( 'sportspress-activation', plugins_url(  '/assets/css/activation.css', SP_PLUGIN_FILE ) );
			add_action( 'admin_notices', array( $this, 'template_file_check_notice' ) );
		}
	}

	/**
	 * Show the setup notices
	 */
	public function setup_notice() {
		include( 'views/html-notice-install.php' );
	}

	/**
	 * Displays a notice when the user doesn't have access to edit a post type
	 */
	public function no_access_notice() {
		include( 'views/html-notice-no-access.php' );
	}

	/**
	 * Show the Theme Check notice
	 */
	public function theme_check_notice() {
		include( 'views/html-notice-theme-support.php' );
	}

	/**
	 * Show a notice highlighting bad template files
	 */
	public function template_file_check_notice() {
		if ( isset( $_GET['page'] ) && 'sportspress' == $_GET['page'] && isset( $_GET['tab'] ) && 'status' == $_GET['tab'] ) {
			return;
		}

		$status         = include( 'class-sp-admin-status.php' );
		$core_templates = $status->scan_template_files( SP()->plugin_path() . '/templates' );
		$outdated       = false;

		foreach ( $core_templates as $file ) {
			$theme_file = false;
			if ( file_exists( get_stylesheet_directory() . '/' . $file ) ) {
				$theme_file = get_stylesheet_directory() . '/' . $file;
			} elseif ( file_exists( get_stylesheet_directory() . '/sportspress/' . $file ) ) {
				$theme_file = get_stylesheet_directory() . '/sportspress/' . $file;
			} elseif ( file_exists( get_template_directory() . '/' . $file ) ) {
				$theme_file = get_template_directory() . '/' . $file;
			} elseif( file_exists( get_template_directory() . '/sportspress/' . $file ) ) {
				$theme_file = get_template_directory() . '/sportspress/' . $file;
			}

			if ( $theme_file ) {
				$core_version  = $status->get_file_version( SP()->plugin_path() . '/templates/' . $file );
				$theme_version = $status->get_file_version( $theme_file );

				if ( $core_version && $theme_version && version_compare( $theme_version, $core_version, '<' ) ) {
					$outdated = true;
					break;
				}
			}
		}

		if ( $outdated ) {
			include( 'views/html-notice-template-check.php' );
		}
	}
}

endif;

return new SP_Admin_Notices();