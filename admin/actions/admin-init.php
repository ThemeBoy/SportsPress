<?php
function sportspress_admin_init() {

    $installed = get_option( 'sportspress_installed', false );

    // Define roles and capabilities
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
	
	// Add settings sections
	register_setting(
		'sportspress_general',
		'sportspress',
		'sportspress_validate'
	);
	
	add_settings_section(
		'general',
		'',
		'',
		'sportspress_general'
	);
	
	add_settings_field(	
		'sport',
		__( 'Sport', 'sportspress' ),
		'sportspress_sport_callback',	
		'sportspress_general',
		'general'
	);
	
}
add_action( 'admin_init', 'sportspress_admin_init', 1 );
