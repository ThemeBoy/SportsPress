<?php
if ( ! isset( $id ) )
	$id = get_the_ID();
$staff = (array)get_post_meta( $id, 'sp_staff', false );

$output = '';

echo apply_filters( 'sportspress_event_staff',  $output );
