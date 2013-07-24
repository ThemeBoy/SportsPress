<?php
$sp_options = array(
	'settings' => array(
		'sp_event_team_count' => 2,
	),
);

foreach( $sp_options as $optiongroupkey => $optiongroup ) {
	foreach( $optiongroup as $key => $value ) {
		if ( get_option( $key ) === false )
			update_option( $key, $value );
	}
}
?>