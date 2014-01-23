<?php
function sportspress_activation_hook() {

    // Team Manager
    remove_role( 'sp_site_admin' );
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
