<?php
global $sportspress_options;

$defaults = array(
	'id' => get_the_ID(),
	'number' => -1,
	'performance' => null,
	'orderby' => 'default',
	'order' => 'ASC',
	'show_all_players_link' => false,
	'link_posts' => get_option( 'sportspress_list_link_players', 'yes' ) == 'yes' ? true : false,
	'sortable' => get_option( 'sportspress_enable_sortable_tables', 'yes' ) == 'yes' ? true : false,
	'responsive' => get_option( 'sportspress_enable_responsive_tables', 'yes' ) == 'yes' ? true : false,
);

extract( $defaults, EXTR_SKIP );

$output = '<div class="sp-table-wrapper">' .
	'<table class="sp-player-list sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . ( $sortable ? ' sp-sortable-table' : '' ) . '">' . '<thead>' . '<tr>';

$data = sportspress_get_player_list_data( $id );

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( $orderby == 'default' ):
	$orderby = get_post_meta( $id, 'sp_orderby', true );
	$order = get_post_meta( $id, 'sp_order', true );
else:
	global $sportspress_performance_priorities;
	$sportspress_performance_priorities = array(
		array(
			'key' => $orderby,
			'order' => $order,
		),
	);
	uasort( $data, 'sportspress_sort_list_players' );
endif;

if ( in_array( $orderby, array( 'number', 'name' ) ) ):
	$output .= '<th class="data-number">#</th>';
else:
	$output .= '<th class="data-rank">' . SP()->text->string('Rank', 'player') . '</th>';
endif;

foreach( $labels as $key => $label ):
	if ( ! is_array( $performance ) || $key == 'name' || in_array( $key, $performance ) )
	$output .= '<th class="data-' . $key . '">'. $label . '</th>';
endforeach;

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;

if ( is_int( $number ) && $number > 0 )
	$limit = $number;

foreach( $data as $player_id => $row ):
	if ( isset( $limit ) && $i >= $limit ) continue;

	$name = sportspress_array_value( $row, 'name', null );
	if ( ! $name ) continue;

	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	// Rank or number
	if ( isset( $orderby ) && $orderby != 'number' ):
		$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';
	else:
		$number = get_post_meta( $player_id, 'sp_number', true );
		$output .= '<td class="data-number">' . ( $number ? $number : '&nbsp;' ) . '</td>';
	endif;

	if ( $link_posts ):
		$permalink = get_post_permalink( $player_id );
		$name = '<a href="' . $permalink . '">' . $name . '</a>';
	endif;

	$output .= '<td class="data-name">' . $name . '</td>';

	foreach( $labels as $key => $value ):
		if ( $key == 'name' )
			continue;
		if ( ! is_array( $performance ) || in_array( $key, $performance ) )
		$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
	endforeach;

	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody>' . '</table>' . '</div>';

if ( $show_all_players_link )
	$output .= '<a class="sp-player-list-link" href="' . get_permalink( $id ) . '">' . SP()->text->string('View all players', 'player') . '</a>';

echo apply_filters( 'sportspress_player_list',  $output );
