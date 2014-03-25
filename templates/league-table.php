<?php
global $sportspress_options;

$defaults = array(
	'id' => get_the_ID(),
	'number' => -1,
	'columns' => null,
	'show_full_table_link' => false,
	'show_team_logo' => sportspress_array_value( $sportspress_options, 'league_table_show_team_logo', false ),
	'link_posts' => sportspress_array_value( $sportspress_options, 'league_table_link_posts', false ),
	'sortable' => sportspress_array_value( $sportspress_options, 'league_table_sortable', true ),
	'responsive' => sportspress_array_value( $sportspress_options, 'league_table_responsive', true ),
);

extract( $defaults, EXTR_SKIP );

$output = '<div class="sp-table-wrapper">' .
	'<table class="sp-league-table sp-data-table' . ( $responsive ? ' sp-responsive-table' : '' ) . ( $sortable ? ' sp-sortable-table' : '' ) . '">' . '<thead>' . '<tr>';

$data = sportspress_get_league_table_data( $id );

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

if ( ! $columns )
	$columns = get_post_meta( $id, 'sp_columns', true );

if ( ! is_array( $columns ) )
	$columns = explode( ',', $columns );

$output .= '<th class="data-rank">' . __( 'Pos', 'sportspress' ) . '</th>';

foreach( $labels as $key => $label ):
	if ( ! is_array( $columns ) || $key == 'name' || in_array( $key, $columns ) )
		$output .= '<th class="data-' . $key . '">' . $label . '</th>';
endforeach;

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;

if ( is_int( $number ) && $number > 0 )
	$limit = $number;

foreach( $data as $team_id => $row ):

	if ( isset( $limit ) && $i >= $limit ) continue;

	$name = sportspress_array_value( $row, 'name', null );
	if ( ! $name ) continue;

	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	// Rank
	$output .= '<td class="data-rank">' . ( $i + 1 ) . '</td>';

	if ( $show_team_logo )
		$name = get_the_post_thumbnail( $team_id, 'sportspress-fit-icon', array( 'class' => 'team-logo' ) ) . ' ' . $name;

	if ( $link_posts ):
		$permalink = get_post_permalink( $team_id );
		$name = '<a href="' . $permalink . '">' . $name . '</a>';
	endif;

	$output .= '<td class="data-name">' . $name . '</td>';

	foreach( $labels as $key => $value ):
		if ( $key == 'name' )
			continue;
		if ( ! is_array( $columns ) || in_array( $key, $columns ) )
			$output .= '<td class="data-' . $key . '">' . sportspress_array_value( $row, $key, '&mdash;' ) . '</td>';
	endforeach;

	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody>' . '</table>';

if ( $show_full_table_link )
	$output .= '<a class="sp-league-table-link" href="' . get_permalink( $id ) . '">' . __( 'View full table', 'sportspress' ) . '</a>';

$output .= '</div>';

echo $output;
