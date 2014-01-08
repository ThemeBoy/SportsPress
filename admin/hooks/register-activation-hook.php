<?php
function sportspress_activation_hook() {

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

    // Flush rewrite rules
    sportspress_event_post_init();
    sportspress_result_post_init();
    sportspress_outcome_post_init();
    sportspress_column_post_init();
    sportspress_statistic_post_init();
    sportspress_team_post_init();
    sportspress_table_post_init();
    sportspress_player_post_init();
    sportspress_list_post_init();
    sportspress_staff_post_init();
    sportspress_position_term_init();
    sportspress_season_term_init();
    flush_rewrite_rules();
}
register_activation_hook( SPORTSPRESS_PLUGIN_FILE, 'sportspress_activation_hook' );
