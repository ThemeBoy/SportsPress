<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

sp_get_template( 'player-metrics.php', array( 'id' => $id ) );
sp_get_template( 'player-performance.php', array( 'id' => $id ) );
