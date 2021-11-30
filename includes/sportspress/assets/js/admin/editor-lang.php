<?php

$shortcodes = '';

$options = array(
	'event'  => array(
		'details',
		'results',
		'performance',
		'venue',
		'officials',
		'teams',
		'full',
	),
	'team'   => array(),
	'player' => array(
		'details',
		'statistics',
	),
);

$options = apply_filters( 'sportspress_shortcodes', $options );

foreach ( $options as $name => $group ) {
	if ( empty( $group ) ) {
		continue;
	}
	$shortcodes .= $name . '[' . implode( '|', $group ) . ']';
}

$raw = apply_filters(
	'sportspress_tinymce_strings',
	array(
		'shortcodes'  => $shortcodes,
		'insert'      => esc_attr__( 'SportsPress Shortcodes', 'sportspress' ),
		'auto'        => esc_attr__( 'Auto', 'sportspress' ),
		'manual'      => esc_attr__( 'Manual', 'sportspress' ),
		'select'      => esc_attr__( 'Select...', 'sportspress' ),
		'event'       => esc_attr__( 'Event', 'sportspress' ),
		'details'     => esc_attr__( 'Details', 'sportspress' ),
		'results'     => esc_attr__( 'Results', 'sportspress' ),
		'countdown'   => esc_attr__( 'Countdown', 'sportspress' ),
		'performance' => esc_attr__( 'Box Score', 'sportspress' ),
		'venue'       => esc_attr__( 'Venue', 'sportspress' ),
		'officials'   => esc_attr__( 'Officials', 'sportspress' ),
		'teams'       => esc_attr__( 'Teams', 'sportspress' ),
		'full'        => esc_attr__( 'Full Info', 'sportspress' ),
		'calendar'    => esc_attr__( 'Calendar', 'sportspress' ),
		'statistics'  => esc_attr__( 'Statistics', 'sportspress' ),
		'team'        => esc_attr__( 'Team', 'sportspress' ),
		'standings'   => esc_attr__( 'League Table', 'sportspress' ),
		'player'      => esc_attr__( 'Player', 'sportspress' ),
		'list'        => esc_attr__( 'List', 'sportspress' ),
		'blocks'      => esc_attr__( 'Blocks', 'sportspress' ),
		'gallery'     => esc_attr__( 'Gallery', 'sportspress' ),
	)
);

$formatted = array();

foreach ( $raw as $key => $value ) {
	$formatted[] = $key . ': "' . esc_js( $value ) . '"';
}

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ':{
    sportspress:{
        ' . implode( ', ', $formatted ) . '
    }
}})';
