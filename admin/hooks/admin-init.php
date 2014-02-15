<?php
function sportspress_team_importer() {
    require_once ABSPATH . 'wp-admin/includes/import.php';

    if ( ! class_exists( 'WP_Importer' ) ) {
        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        if ( file_exists( $class_wp_importer ) )
            require $class_wp_importer;
    }

    // includes
    require dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/importers/team-importer.php';

    // Dispatch
    $importer = new SP_Team_Importer();
    $importer->dispatch();
}

function sportspress_player_importer() {
    require_once ABSPATH . 'wp-admin/includes/import.php';

    if ( ! class_exists( 'WP_Importer' ) ) {
        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        if ( file_exists( $class_wp_importer ) )
            require $class_wp_importer;
    }

    // includes
    require dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/importers/player-importer.php';

    // Dispatch
    $importer = new SP_Player_Importer();
    $importer->dispatch();
}

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
        'read',
        'read_private',
        'edit',
        'edit_others',
        'edit_private',
        'edit_published',
        'publish',
        'delete',
        'delete_others',
        'delete_private',
        'delete_published',
    );

    // Site Admin
    $administrator = get_role( 'administrator' );

    foreach( $post_types as $post_type ):
        $administrator->add_cap( 'read_' . $post_type );
        $administrator->add_cap( 'edit_' . $post_type );
        $administrator->add_cap( 'delete_' . $post_type );
        foreach ( $caps as $cap ):
            $administrator->add_cap( $cap . '_' . $post_type . 's' );
        endforeach;
    endforeach;

    // Importers
    register_importer( 'sportspress_team_csv', __( 'SportsPress Teams (CSV)', 'sportspress' ), __( 'Import <strong>teams</strong> from a csv file.', 'sportspress'), 'sportspress_team_importer' );
    register_importer( 'sportspress_player_csv', __( 'SportsPress Players (CSV)', 'sportspress' ), __( 'Import <strong>players</strong> from a csv file.', 'sportspress'), 'sportspress_player_importer' );
}
add_action( 'admin_init', 'sportspress_admin_init' );
