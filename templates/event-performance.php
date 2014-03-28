<?php
if ( ! isset( $id ) )
	$id = get_the_ID();

$teams = (array)get_post_meta( $id, 'sp_team', false );
$staff = (array)get_post_meta( $id, 'sp_staff', false );
$stats = (array)get_post_meta( $id, 'sp_players', true );
$performance_labels = sp_get_var_labels( 'sp_performance' );
$link_posts = get_option( 'sportspress_event_link_players', 'yes' ) == 'yes' ? true : false;
$sortable = get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false;
$responsive = get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false;

$output = '';

foreach( $teams as $key => $team_id ):
	if ( ! $team_id ) continue;

	$totals = array();

	// Get results for players in the team
	$players = sp_array_between( (array)get_post_meta( $id, 'sp_player', false ), 0, $key );

	if ( sizeof( $players ) <= 1 ) continue;

	$data = sp_array_combine( $players, sp_array_value( $stats, $team_id, array() ) );

	$output .= '<h3>' . get_the_title( $team_id ) . '</h3>';

	$output .= '<div class="sp-table-wrapper">' .
		'<table class="sp-event-performance sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . ( $sortable ? ' sp-sortable-table' : '' ) . '">' . '<thead>' . '<tr>';

	$output .= '<th class="data-number">#</th>';
	$output .= '<th class="data-name">' . SP()->text->string('Player', 'event') . '</th>';

	foreach( $performance_labels as $key => $label ):
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
	endforeach;

	$output .= '</tr>' . '</thead>' . '<tbody>';

	$i = 0;

	foreach( $data as $player_id => $row ):

		if ( ! $player_id )
			continue;

		$name = get_the_title( $player_id );

		if ( ! $name )
			continue;

		$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		$number = get_post_meta( $player_id, 'sp_number', true );

		// Player number
		$output .= '<td class="data-number">' . $number . '</td>';

		if ( $link_posts ):
			$permalink = get_post_permalink( $player_id );
			$name =  '<a href="' . $permalink . '">' . $name . '</a>';
		endif;

		$output .= '<td class="data-name">' . $name . '</td>';

		foreach( $performance_labels as $key => $label ):
			if ( $key == 'name' )
				continue;
			if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
				$value = $row[ $key ];
			else:
				$value = 0;
			endif;
			if ( ! array_key_exists( $key, $totals ) ):
				$totals[ $key ] = 0;
			endif;
			$totals[ $key ] += $value;
			$output .= '<td class="data-' . $key . '">' . $value . '</td>';
		endforeach;

		$output .= '</tr>';

		$i++;

	endforeach;

	$output .= '</tbody>';

	if ( array_key_exists( 0, $data ) ):

		$output .= '<tfoot><tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

		$number = get_post_meta( $player_id, 'sp_number', true );

		// Player number
		$output .= '<td class="data-number">&nbsp;</td>';
		$output .= '<td class="data-name">' . SP()->text->string('Total', 'event') . '</td>';

		$row = $data[0];

		foreach( $performance_labels as $key => $label ):
			if ( $key == 'name' ):
				continue;
			endif;
			if ( array_key_exists( $key, $row ) && $row[ $key ] != '' ):
				$value = $row[ $key ];
			else:
				$value = sp_array_value( $totals, $key, 0 );
			endif;
			$output .= '<td class="data-' . $key . '">' . $value . '</td>';
		endforeach;

		$output .= '</tr></tfoot>';

	endif;

	$output .= '</table>' . '</div>';

endforeach;

echo apply_filters( 'sportspress_event_performance',  $output );
