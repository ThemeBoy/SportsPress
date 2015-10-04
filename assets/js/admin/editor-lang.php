<?php

$shortcodes = '';

$options = array(
    'event' => array(
        'details', 'results', 'performance'
    ),
    'player' => array(
        'details', 'statistics'
    ),
);

$options = apply_filters( 'sportspress_shortcodes', $options );

foreach ( $options as $name => $group ) {
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
    'performance' =>  __( 'Scorecard', 'sportspress' ),
    'calendar' =>  __( 'Calendar', 'sportspress' ),
    'statistics' =>  __( 'Statistics', 'sportspress' ),
    'table' =>  __( 'League Table', 'sportspress' ),
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
