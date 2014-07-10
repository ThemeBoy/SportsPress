<?php

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ':{
    sportspress:{
        insert: "' . esc_js( __( 'SportsPress shortcodes', 'sportspress' ) ) . '",
        countdown: "' . esc_js( __( 'Countdown', 'sportspress' ) ) . '",
        event_details: "' . esc_js( __( 'Event Details', 'sportspress' ) ) . '",
        event_results: "' . esc_js( __( 'Event Results', 'sportspress' ) ) . '",
        event_performance: "' . esc_js( __( 'Event Performance', 'sportspress' ) ) . '",
        event_calendar: "' . esc_js( __( 'Event Calendar', 'sportspress' ) ) . '",
        event_list: "' . esc_js( __( 'Event List', 'sportspress' ) ) . '",
        event_blocks: "' . esc_js( __( 'Event Blocks', 'sportspress' ) ) . '",
        league_table: "' . esc_js( __( 'League Table', 'sportspress' ) ) . '",
        player_list: "' . esc_js( __( 'Player List', 'sportspress' ) ) . '",
        player_gallery: "' . esc_js( __( 'Player Gallery', 'sportspress' ) ) . '"
    }
}})';