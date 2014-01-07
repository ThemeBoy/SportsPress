<?php
if ( !function_exists( 'sportspress_install' ) ) {
	function sportspress_install() {
	    $installed = get_option( 'sportspress_installed', false );
		if ( ! $installed ):

		    $role = get_role( 'administrator' );

		    // Events
		    $role->add_cap( 'edit_sp_event' );
		    $role->add_cap( 'edit_sp_events' );
		    $role->add_cap( 'edit_others_sp_events' );
		    $role->add_cap( 'delete_sp_event' );
		    $role->add_cap( 'publish_sp_events' );
		    $role->add_cap( 'read_sp_events' );
		    $role->add_cap( 'read_private_sp_events' );

		    // Teams
		    $role->add_cap( 'edit_sp_team' );
		    $role->add_cap( 'edit_sp_teams' );
		    $role->add_cap( 'edit_others_sp_teams' );
		    $role->add_cap( 'delete_sp_team' );
		    $role->add_cap( 'publish_sp_teams' );
		    $role->add_cap( 'read_sp_teams' );
		    $role->add_cap( 'read_private_sp_teams' );

		    // League Tables
		    $role->add_cap( 'edit_sp_table' );
		    $role->add_cap( 'edit_sp_tables' );
		    $role->add_cap( 'edit_others_sp_tables' );
		    $role->add_cap( 'delete_sp_table' );
		    $role->add_cap( 'publish_sp_tables' );
		    $role->add_cap( 'read_sp_tables' );
		    $role->add_cap( 'read_private_sp_tables' );

		    // Players
		    $role->add_cap( 'edit_sp_player' );
		    $role->add_cap( 'edit_sp_players' );
		    $role->add_cap( 'edit_others_sp_players' );
		    $role->add_cap( 'delete_sp_player' );
		    $role->add_cap( 'publish_sp_players' );
		    $role->add_cap( 'read_sp_players' );
		    $role->add_cap( 'read_private_sp_players' );

		    // Player Lists
		    $role->add_cap( 'edit_sp_list' );
		    $role->add_cap( 'edit_sp_lists' );
		    $role->add_cap( 'edit_others_sp_lists' );
		    $role->add_cap( 'delete_sp_list' );
		    $role->add_cap( 'publish_sp_lists' );
		    $role->add_cap( 'read_sp_lists' );
		    $role->add_cap( 'read_private_sp_lists' );

		    // Staff
		    $role->add_cap( 'edit_sp_staff' );
		    $role->add_cap( 'edit_sp_staffs' );
		    $role->add_cap( 'edit_others_sp_staffs' );
		    $role->add_cap( 'delete_sp_staff' );
		    $role->add_cap( 'publish_sp_staffs' );
		    $role->add_cap( 'read_sp_staffs' );
		    $role->add_cap( 'read_private_sp_staffs' );

		    // Settings
		    $role->add_cap( 'read_sp_configs' );
		    $role->add_cap( 'read_private_sp_configs' );
		    $role->add_cap( 'edit_sp_config' );
		    $role->add_cap( 'edit_sp_configs' );
		    $role->add_cap( 'edit_published_sp_configs' );
		    $role->add_cap( 'edit_private_sp_configs' );
		    $role->add_cap( 'edit_others_sp_configs' );
		    $role->add_cap( 'delete_sp_config' );
		    $role->add_cap( 'delete_published_sp_configs' );
		    $role->add_cap( 'delete_private_sp_configs' );
		    $role->add_cap( 'delete_others_sp_configs' );
		    $role->add_cap( 'publish_sp_configs' );

		    // Team Manager
			remove_role( 'sp_team_manager' );
			add_role(
			    'sp_team_manager',
			    __( 'Team Manager', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_players' => true,
			        'edit_sp_players' => true,
			        'edit_others_sp_players' => true,
			        'delete_sp_player' => true,
			        'publish_sp_players' => true,
			        'read_sp_staffs' => true,
			        'edit_sp_staffs' => true,
			        'edit_others_sp_staffs' => true,
			        'delete_sp_staff' => true,
			        'publish_sp_staffs' => true
			    )
			);

			// Staff
			remove_role( 'sp_staff' );
			add_role(
			    'sp_staff',
			    __( 'Staff', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_staffs' => true,
			        'edit_sp_staffs' => true,
			        'delete_sp_staff' => true
			    )
			);

			// Player
			remove_role( 'sp_player' );
			add_role(
			    'sp_player',
			    __( 'Player', 'sportspress' ),
			    array(
			        'read' => true,
			        'edit_posts' => true,
			        'delete_posts' => true,
			        'read_sp_players' => true,
			        'edit_sp_players' => true,
			        'delete_sp_player' => true
			    )
			);

			update_option( 'sportspress_installed', 1 );
		endif;

    }
}
add_action( 'admin_init', 'sportspress_install', 1 );

// Flush rewrite rules on activation
function sp_rewrite_flush() {
    sp_event_cpt_init();
    sp_result_cpt_init();
    sp_outcome_cpt_init();
    sp_column_cpt_init();
    sp_statistic_cpt_init();
    sp_team_cpt_init();
    sp_table_cpt_init();
    sp_player_cpt_init();
    sp_list_cpt_init();
    sp_staff_cpt_init();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'sp_rewrite_flush' );

function sp_admin_head_edit() {
	global $typenow;

	if ( in_array( $typenow, array( 'sp_result', 'sp_outcome', 'sp_column', 'sp_statistic' ) ) ):
		sp_highlight_admin_menu();
	endif;
}
add_action( 'admin_head-edit.php', 'sp_admin_head_edit', 10, 2 );
add_action( 'admin_head-post.php', 'sp_admin_head_edit', 10, 2 );
