<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$date = get_the_time( get_option('date_format'), $id );
$time = get_the_time( get_option('time_format'), $id );
$leagues = get_the_terms( $id, 'sp_league' );
$seasons = get_the_terms( $id, 'sp_season' );

$data = array( SP()->text->string('Date', 'event') => $date, SP()->text->string('Time', 'event') => $time );

if ( $leagues ):
	$league = array_pop( $leagues );
	$data[ SP()->text->string('League') ] = $league->name;
endif;

if ( $seasons ):
	$season = array_pop( $seasons );
	$data[ SP()->text->string('Season') ] = $season->name;
endif;

$output = '<h3>' . SP()->text->string('Details', 'event') . '</h3>';

$output .= '<div class="sp-table-wrapper">' .
	'<table class="sp-event-details sp-data-table"><tbody>';

$i = 0;

foreach( $data as $label => $value ):

	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';
	$output .= '<th>' . $label . '</th>';
	$output .= '<td>' . $value . '</td>';
	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody></table></div>';

echo apply_filters( 'sportspress_event_details',  $output );
