<?php
/**
 * Player Statistics for Single League when Additional Statistics are set
 *
 * @author 		ThemeBoy
 * @category 	Admin
 * @package 	SportsPress/Admin/Meta_Boxes
 * @version   2.6.0
 */
 
if ( !isset( $id ) )
	$id = get_the_ID();
//Create new Player class instance to use for new data
$player2 = new SP_Player_Additional( $id );
//Get team_ids for use during iterations
$team_ids = array_filter( get_post_meta( $id, 'sp_team', false ) );
$teams = get_posts( array( 'post_type' => 'sp_team', 'include' => $team_ids ) );
foreach ( $teams as $team ) {
	$teams[ $team->post_title ] = $team->ID;
}

// Check if there are additional stats for our player
$additional_stats = get_post_meta( $id , 'sp_additional_statistics' , true );

// The first row should be column labels
$labels = $data[0];

// Remove the first row to leave us with the actual data
unset( $data[0] );

// Skip if there are no rows in the table
if ( empty( $data ) )
	return;

$output = '<h4 class="sp-table-caption">' . $caption . '</h4>' .
	'<div class="sp-table-wrapper">' .
	'<table class="sp-player-statistics sp-data-table' . ( $scrollable ? ' sp-scrollable-table' : '' ) . '">' . '<thead>' . '<tr>';

foreach( $labels as $key => $label ):
	if ( isset( $hide_teams ) && 'team' == $key )
		continue;
	$output .= '<th class="data-' . $key . '">' . $label . '</th>';
endforeach;

$output .= '</tr>' . '</thead>' . '<tbody>';

$i = 0;

foreach( $data as $season_id => $row ):
		//If additional stats are present then recreate the current season rows
		if ( isset ( $additional_stats[$league_id][$season_id] ) ){
			//Recalculate the original season row
			$stats = $player2->data_season_team( $league_id, $season_id, $teams[ strip_tags( sp_array_value( $row, 'team', '-1' ) ) ] );
			$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';
			foreach( $labels as $key => $value ):
				if ( isset( $hide_teams ) && 'team' == $key )
					continue;
				$output .= '<td class="data-' . $key . ( -1 === $season_id ? ' sp-highlight' : '' ) . '">' . sp_array_value( $stats[ $season_id ], $key, '' ) . '</td>';
			endforeach;
			$output .= '</tr>';
			$i++;
			//Get the additional teams for the current season row
			$additional_teams = array_keys( $additional_stats[ $league_id ][ $season_id ] );
			//Create new season row for each team
			foreach ( $additional_teams as $additional_team ) {
				$date = $additional_stats[ $league_id ][ $season_id ][ $additional_team ][ '_date' ];
				$stats = $player2->data_season_team( $league_id, $season_id, $additional_team, true, false, $date );
				$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';
				foreach( $labels as $key => $value ):
					if ( isset( $hide_teams ) && 'team' == $key )
						continue;
					$output .= '<td class="data-' . $key . ( -1 === $season_id ? ' sp-highlight' : '' ) . '">' . sp_array_value( $stats[ $season_id ], $key, '' ) . '</td>';
				endforeach;
				$output .= '</tr>';
				$i++;
			}
			continue;
		}
	$output .= '<tr class="' . ( $i % 2 == 0 ? 'odd' : 'even' ) . '">';

	foreach( $labels as $key => $value ):
		if ( isset( $hide_teams ) && 'team' == $key )
			continue;
		$output .= '<td class="data-' . $key . ( -1 === $season_id ? ' sp-highlight' : '' ) . '">' . sp_array_value( $row, $key, '' ) . '</td>';
	endforeach;

	$output .= '</tr>';

	$i++;

endforeach;

$output .= '</tbody>' . '</table>' . '</div>';
?>
<div class="sp-template sp-template-player-statistics">
	<?php echo $output; ?>
</div>
