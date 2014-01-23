<?php
function sportspress_activation_hook() {

    $admin_capabilites = array(
        'read_sp_events',
        'read_private_sp_events',
        'edit_sp_event',
        'edit_sp_events',
        'edit_published_sp_events',
        'edit_private_sp_events',
        'edit_others_sp_events',
        'delete_sp_event',
        'delete_published_sp_events',
        'delete_private_sp_events',
        'delete_others_sp_events',
        'publish_sp_events',
        'read_sp_calendars',
        'read_private_sp_calendars',
        'edit_sp_calendar',
        'edit_sp_calendars',
        'edit_published_sp_calendars',
        'edit_private_sp_calendars',
        'edit_others_sp_calendars',
        'delete_sp_calendar',
        'delete_published_sp_calendars',
        'delete_private_sp_calendars',
        'delete_others_sp_calendars',
        'publish_sp_calendars',
        'read_sp_teams',
        'read_private_sp_teams',
        'edit_sp_team',
        'edit_sp_teams',
        'edit_published_sp_teams',
        'edit_private_sp_teams',
        'edit_others_sp_teams',
        'delete_sp_team',
        'delete_published_sp_teams',
        'delete_private_sp_teams',
        'delete_others_sp_teams',
        'publish_sp_teams',
        'read_sp_tables',
        'read_private_sp_tables',
        'edit_sp_table',
        'edit_sp_tables',
        'edit_published_sp_tables',
        'edit_private_sp_tables',
        'edit_others_sp_tables',
        'delete_sp_table',
        'delete_published_sp_tables',
        'delete_private_sp_tables',
        'delete_others_sp_tables',
        'publish_sp_tables',
        'read_sp_players',
        'read_private_sp_players',
        'edit_sp_player',
        'edit_sp_players',
        'edit_published_sp_players',
        'edit_private_sp_players',
        'edit_others_sp_players',
        'delete_sp_player',
        'delete_published_sp_players',
        'delete_private_sp_players',
        'delete_others_sp_players',
        'publish_sp_players',
        'read_sp_lists',
        'read_private_sp_lists',
        'edit_sp_list',
        'edit_sp_lists',
        'edit_published_sp_lists',
        'edit_private_sp_lists',
        'edit_others_sp_lists',
        'delete_sp_list',
        'delete_published_sp_lists',
        'delete_private_sp_lists',
        'delete_others_sp_lists',
        'publish_sp_lists',
        'read_sp_staffs',
        'read_private_sp_staffs',
        'edit_sp_staff',
        'edit_sp_staffs',
        'edit_published_sp_staffs',
        'edit_private_sp_staffs',
        'edit_others_sp_staffs',
        'delete_sp_staff',
        'delete_published_sp_staffs',
        'delete_private_sp_staffs',
        'delete_others_sp_staffs',
        'publish_sp_staffs',
        'read_sp_configs',
        'read_private_sp_configs',
        'edit_sp_config',
        'edit_sp_configs',
        'edit_published_sp_configs',
        'edit_private_sp_configs',
        'edit_others_sp_configs',
        'delete_sp_config',
        'delete_published_sp_configs',
        'delete_private_sp_configs',
        'delete_others_sp_configs',
        'publish_sp_configs',
    );

    // Site Admin
    $role = get_role( 'administrator' );

    foreach( $admin_capabilities as $capability ):
        $role->add_cap( $capability );
    endforeach;

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
