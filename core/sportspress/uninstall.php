<?php
/**
 * SportsPress Uninstall
 *
 * Uninstalling SportsPress deletes user roles and options.
 *
 * @author 		ThemeBoy
 * @category 	Core
 * @package 	SportsPress/Uninstaller
 * @version     0.7
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

global $wpdb, $wp_roles;

$status_options = get_option( 'sportspress_status_options', array() );

// Roles + caps
$installer = include( 'includes/class-sp-install.php' );
$installer->remove_roles();

// Delete options
$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'sportspress_%';");

delete_option( 'sportspress_installed' );