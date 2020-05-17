<?php
/**
 * SportsPress Admin Functions
 *
 * @author      ThemeBoy
 * @category    Core
 * @package     SportsPress/Admin/Functions
 * @version     2.5.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get all SportsPress screen ids
 *
 * @return array
 */
function sp_get_screen_ids() {
    return apply_filters( 'sportspress_screen_ids', array(
        'admin',
        'widgets',
        'dashboard',
        'dashboard_page_sp-about',
        'dashboard_page_sp-credits',
        'dashboard_page_sp-translators',
        'toplevel_page_sportspress',
    	'edit-sp_result',
    	'sp_result',
    	'edit-sp_outcome',
    	'sp_outcome',
    	'edit-sp_performance',
    	'sp_performance',
    	'edit-sp_column',
    	'sp_column',
    	'edit-sp_metric',
    	'sp_metric',
    	'edit-sp_statistic',
    	'sp_statistic',
    	'edit-sp_event',
    	'sp_event',
    	'edit-sp_calendar',
    	'sp_calendar',
    	'edit-sp_team',
    	'sp_team',
    	'edit-sp_table',
    	'sp_table',
    	'edit-sp_player',
    	'sp_player',
    	'edit-sp_list',
    	'sp_list',
    	'edit-sp_staff',
    	'sp_staff',
    	'edit-sp_venue',
    	'edit-sp_league',
    	'edit-sp_season',
        'edit-sp_position',
        'edit-sp_role',
    ) );
}

function add_codemirror_to_custom_css() {
	// Enqueue code editor and settings for manipulating HTML.
	$settings = wp_enqueue_code_editor( array( 'type' => 'css' ) );
	// Bail if user disabled CodeMirror.
	if ( false === $settings ) {
		return;
	}
	wp_add_inline_script(
		'code-editor',
		sprintf(
		'jQuery( function() { wp.codeEditor.initialize( "sportspress_custom_css", %s ); } );',
		wp_json_encode( $settings )
		)
	);
}
if ( function_exists( 'wp_enqueue_code_editor' ) ) {
    add_action( 'sportspress_settings_general', 'add_codemirror_to_custom_css' );
}