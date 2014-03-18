<?php
function sportspress_activation_hook() {

    // League Manager
    remove_role( 'sp_league_manager' );
    add_role(
        'sp_league_manager',
        __( 'League Manager', 'sportspress' ),
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'upload_files' => true,

            'edit_sp_player' => true,
            'edit_sp_players' => true,
            'edit_others_sp_players' => true,
            'edit_private_sp_players' => true,
            'edit_published_sp_players' => true,
            'read_sp_players' => true,
            'read_private_sp_players' => true,
            'publish_sp_players' => true,
            'delete_sp_players' => true,
            'delete_others_sp_players' => true,
            'delete_private_sp_players' => true,
            'delete_published_sp_players' => true,

            'edit_sp_staff' => true,
            'edit_sp_staffs' => true,
            'edit_others_sp_staffs' => true,
            'edit_private_sp_staffs' => true,
            'edit_published_sp_staffs' => true,
            'read_sp_staffs' => true,
            'read_private_sp_staffs' => true,
            'publish_sp_staffs' => true,
            'delete_sp_staffs' => true,
            'delete_others_sp_staffs' => true,
            'delete_private_sp_staffs' => true,
            'delete_published_sp_staffs' => true,

            'edit_sp_team' => true,
            'edit_sp_teams' => true,
            'edit_others_sp_teams' => true,
            'edit_private_sp_teams' => true,
            'edit_published_sp_teams' => true,
            'read_sp_teams' => true,
            'read_private_sp_teams' => true,
            'publish_sp_teams' => true,
            'delete_sp_teams' => true,
            'delete_others_sp_teams' => true,
            'delete_private_sp_teams' => true,
            'delete_published_sp_teams' => true,

            'edit_sp_list' => true,
            'edit_sp_lists' => true,
            'edit_others_sp_lists' => true,
            'edit_private_sp_lists' => true,
            'edit_published_sp_lists' => true,
            'read_sp_lists' => true,
            'read_private_sp_lists' => true,
            'publish_sp_lists' => true,
            'delete_sp_lists' => true,
            'delete_others_sp_lists' => true,
            'delete_private_sp_lists' => true,
            'delete_published_sp_lists' => true,

            'edit_sp_table' => true,
            'edit_sp_tables' => true,
            'edit_private_sp_tables' => true,
            'edit_published_sp_tables' => true,
            'read_sp_tables' => true,
            'read_private_sp_tables' => true,
            'publish_sp_tables' => true,
            'delete_sp_tables' => true,
            'delete_private_sp_tables' => true,
            'delete_published_sp_tables' => true,

            'view_sportspress_reports' => true,
        )
    );

    // Team Manager
    remove_role( 'sp_team_manager' );
    add_role(
        'sp_team_manager',
        __( 'Team Manager', 'sportspress' ),
        array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => true,
            'upload_files' => true,

            'edit_sp_player' => true,
            'edit_sp_players' => true,
            'edit_private_sp_players' => true,
            'edit_published_sp_players' => true,
            'read_sp_players' => true,
            'read_private_sp_players' => true,
            'publish_sp_players' => true,
            'delete_sp_players' => true,
            'delete_private_sp_players' => true,
            'delete_published_sp_players' => true,

            'edit_sp_staff' => true,
            'edit_sp_staffs' => true,
            'edit_private_sp_staffs' => true,
            'edit_published_sp_staffs' => true,
            'read_sp_staffs' => true,
            'read_private_sp_staffs' => true,
            'publish_sp_staffs' => true,
            'delete_sp_staffs' => true,
            'delete_private_sp_staffs' => true,
            'delete_published_sp_staffs' => true,

            'edit_sp_team' => true,
            'edit_sp_teams' => true,
            'read_sp_teams' => true,
            'delete_sp_teams' => true,

            'edit_sp_list' => true,
            'edit_sp_lists' => true,
            'read_sp_lists' => true,
            'delete_sp_lists' => true,
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
            'upload_files' => true,

            'edit_sp_staff' => true,
            'edit_sp_staffs' => true,
            'read_sp_staffs' => true,
            'delete_sp_staffs' => true,
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
            'upload_files' => true,

            'edit_sp_player' => true,
            'edit_sp_players' => true,
            'read_sp_players' => true,
            'delete_sp_players' => true,
        )
    );

    sportspress_flush_rewrite_rules();
}
register_activation_hook( SPORTSPRESS_PLUGIN_FILE, 'sportspress_activation_hook' );
