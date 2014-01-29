<?php
function sportspress_admin_init() {
    $post_types = array(
        'sp_event',
        'sp_team',
        'sp_table',
        'sp_player',
        'sp_list',
        'sp_staff',
        'sp_config',
    );

    $caps = array(
        'publish',
        'read',
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
        $administrator->add_cap( 'edit_' . $post_type );
        foreach ( $caps as $cap ):
            $administrator->add_cap( $cap . '_' . $post_type . 's' );
        endforeach;
    endforeach;
}
add_action( 'admin_init', 'sportspress_admin_init' );
