<?php

$shortcodes = '';

$options = array(
    'event' => array(
        'details', 'results', 'performance', 'venue', 'officials', 'teams', 'full',
    ),
    'team' => array(),
    'player' => array(
        'details', 'statistics'
    ),
);

$options = apply_filters( 'sportspress_shortcodes', $options );

foreach ( $options as $name => $group ) {
    if ( empty( $group ) ) continue;
    $shortcodes .= $name . '[' . implode( '|', $group ) . ']';
}

$raw = apply_filters( 'sportspress_tinymce_strings', array(
    'shortcodes' =>  $shortcodes,
    'insert' =>  __( 'SportsPress Shortcodes', 'sportspress' ),
    'auto' =>  __( 'Auto', 'sportspress' ),
    'manual' =>  __( 'Manual', 'sportspress' ),
    'select' =>  __( 'Select...', 'sportspress' ),
    'event' =>  __( 'Event', 'sportspress' ),
    'details' =>  __( 'Details', 'sportspress' ),
    'results' =>  __( 'Results', 'sportspress' ),
    'countdown' =>  __( 'Countdown', 'sportspress' ),
    'performance' =>  __( 'Box Score', 'sportspress' ),
    'venue' =>  __( 'Venue', 'sportspress' ),
    'officials' =>  __( 'Officials', 'sportspress' ),
    'teams' =>  __( 'Teams', 'sportspress' ),
    'full' =>  __( 'Full Info', 'sportspress' ),
    'calendar' =>  __( 'Calendar', 'sportspress' ),
    'statistics' =>  __( 'Statistics', 'sportspress' ),
    'team' =>  __( 'Team', 'sportspress' ),
    'standings' =>  __( 'League Table', 'sportspress' ),
    'player' =>  __( 'Player', 'sportspress' ),
    'list' =>  __( 'List', 'sportspress' ),
    'blocks' =>  __( 'Blocks', 'sportspress' ),
    'gallery' =>  __( 'Gallery', 'sportspress' ),
));

$formatted = array();

foreach ( $raw as $key => $value ) {
    $formatted[] = $key . ': "' . esc_js( $value ) . '"';
}

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ':{
    sportspress:{
        ' . implode( ', ', $formatted ) . '
    }
}})';
