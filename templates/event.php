<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

sp_get_template( 'event-video.php', array( 'id' => $id ) );
sp_get_template( 'event-results.php', array( 'id' => $id ) );
sp_get_template( 'event-details.php', array( 'id' => $id ) );
sp_get_template( 'event-venue.php', array( 'id' => $id ) );
sp_get_template( 'event-performance.php', array( 'id' => $id ) );
