<?php
$sportspress_options = array(
	'settings' => array(
		'sp_event_team_count' => 2,
	)
);

$sportspress_texts = array(
	'sp_team' => array(
		'Enter title here' => __( 'Team', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Logo', 'sportspress' ) ),
		'Parent' => sprintf( __( 'Parent %s', 'sportspress' ), __( 'Team', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Logo', 'sportspress' ) )
	),
	'sp_player' => array(
		'Enter title here' => __( 'Name', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) )
	),
	'sp_staff' => array(
		'Enter title here' => __( 'Name', 'sportspress' ),
		'Set featured image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Set Featured Image' => sprintf( __( 'Add %s', 'sportspress' ), __( 'Photo', 'sportspress' ) ),
		'Remove featured image' => sprintf( __( 'Remove %s', 'sportspress' ), __( 'Photo', 'sportspress' ) )
	)
);

foreach( $sportspress_options as $optiongroupkey => $optiongroup ) {
	foreach( $optiongroup as $key => $value ) {
		if ( get_option( $key ) === false )
			update_option( $key, $value );
	}
}
?>