<?php
function sportspress_admin_notices_styles() {
	$screen = get_current_screen();

	if ( $screen->id != 'settings_page_sportspress' ):
		if ( isset( $_REQUEST['sportspress_installed'] ) ):
			update_option( 'sportspress_installed', $_REQUEST['sportspress_installed'] );
		endif;

		if ( ! get_option( 'sportspress_installed' ) ):
			add_action( 'admin_notices', 'sportspress_admin_install_notices' );
		endif;
	endif;

	$template = get_option( 'template' );

	if ( ! current_theme_supports( 'sportspress' ) && ! in_array( $template, array( 'twentyfourteen', 'twentythirteen', 'twentyeleven', 'twentytwelve', 'twentyten' ) ) ):
		if ( ! empty( $_GET['hide_sportspress_theme_support_check'] ) ):
			update_option( 'sportspress_theme_support_check', $template );
			return;
		endif;

		if ( get_option( 'sportspress_theme_support_check' ) !== $template ):
			add_action( 'admin_notices', 'sportspress_theme_check_notice' );
		endif;
	endif;
}
add_action( 'admin_print_styles', 'sportspress_admin_notices_styles' );

/**
 * sportspress_admin_install_notices function.
 *
 * @access public
 * @return void
 */
function sportspress_admin_install_notices() {
//	include( dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/includes/notice-install.php' );
}

/**
 * sportspress_theme_check_notice function.
 *
 * @access public
 * @return void
 */
function sportspress_theme_check_notice() {
//	include( dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/includes/notice-theme-support.php' );
}
