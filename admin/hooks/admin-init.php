<?php
function sportspress_admin_init() {
    $post_types = array(
        'sp_events',
        'sp_calendars',
        'sp_teams',
        'sp_tables',
        'sp_players',
        'sp_lists',
        'sp_staffs',
        'sp_configs',
    );

    $caps = array(
        'publish',
        'delete',
        'delete_others',
        'delete_private',
        'delete_published',
        'edit',
        'edit_others',
        'edit_private',
        'edit_published',
        'read_private',
    );

    // Site Admin
    $administrator = get_role( 'administrator' );

    foreach( $post_types as $post_type ):
        foreach ( $caps as $cap ):
            $administrator->add_cap( $cap . '_' . $post_type );
        endforeach;
    endforeach;
}
add_action( 'admin_init', 'sportspress_admin_init' );
