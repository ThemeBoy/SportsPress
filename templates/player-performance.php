<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$leagues = get_the_terms( $id, 'sp_league' );

// Loop through performance for each league
if ( is_array( $leagues ) ):
	foreach ( $leagues as $league ):
        sp_get_template( 'player-league-performance.php', array(
        	'league' => $league
        ) );
	endforeach;
endif;
