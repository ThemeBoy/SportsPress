<?php
function sportspress_team_importer() {
    require_once ABSPATH . 'wp-admin/includes/import.php';

    if ( ! class_exists( 'WP_Importer' ) ) {
        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        if ( file_exists( $class_wp_importer ) )
            require $class_wp_importer;
    }

    // includes
    require dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/tools/team-importer.php';

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
    require dirname( SPORTSPRESS_PLUGIN_FILE ) . '/admin/tools/player-importer.php';

    // Dispatch
    $importer = new SP_Player_Importer();
    $importer->dispatch();
}

function sportspress_register_importers() {
    register_importer( 'sportspress_team_csv', __( 'SportsPress Teams (CSV)', 'sportspress' ), __( 'Import <strong>teams</strong> from a csv file.', 'sportspress'), 'sportspress_team_importer' );
    register_importer( 'sportspress_player_csv', __( 'SportsPress Players (CSV)', 'sportspress' ), __( 'Import <strong>players</strong> from a csv file.', 'sportspress'), 'sportspress_player_importer' );
}
add_action( 'admin_init', 'sportspress_register_importers' );
